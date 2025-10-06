<?php

namespace App\Filament\Resources\Landlords\RelationManagers;

use App\Models\Property;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    // Basic Information Section
                    Section::make('Basic Information')
                        ->icon('heroicon-o-home')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Textarea::make('description')
                                ->rows(3)
                                ->maxLength(1000)
                                ->columnSpanFull(),
                            Select::make('type')
                                ->options(Property::TYPES)
                                ->required(),
                            Select::make('status')
                                ->options(Property::STATUSES)
                                ->required()
                                ->default('available'),
                        ])->columns(2),

                    // Property Details Section
                    Section::make('Property Details')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            TextInput::make('bedrooms')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(20),
                            TextInput::make('bathrooms')
                                ->numeric()
                                ->step(0.5)
                                ->minValue(0)
                                ->maxValue(20),
                            TextInput::make('area_sqft')
                                ->label('Area (sq ft)')
                                ->numeric()
                                ->minValue(0),
                            TextInput::make('built_year')
                                ->label('Year Built')
                                ->numeric()
                                ->minValue(1800)
                                ->maxValue(now()->year + 2),
                        ])->columns(2),

                ])->columnSpan(2),

                Group::make()->schema([
                    // Location Section
                    Section::make('Location')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            TextInput::make('address')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('city')
                                ->required()
                                ->maxLength(100),
                            TextInput::make('state_province')
                                ->label('State/Province')
                                ->maxLength(100),
                            TextInput::make('postal_code')
                                ->maxLength(20),
                            TextInput::make('country')
                                ->default('US')
                                ->maxLength(10),
                        ])->columns(1),

                    // Rental Information Section
                    Section::make('Rental Information')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            TextInput::make('rent_amount')
                                ->label('Rent Amount')
                                ->required()
                                ->numeric()
                                ->prefix('$'),
                            Select::make('rent_period')
                                ->label('Rent Period')
                                ->options(Property::RENT_PERIODS)
                                ->default('monthly')
                                ->required(),
                            TextInput::make('security_deposit')
                                ->label('Security Deposit')
                                ->numeric()
                                ->prefix('$'),
                        ])->columns(1),

                    // Features Section
                    Section::make('Features')
                        ->icon('heroicon-o-star')
                        ->schema([
                            Toggle::make('furnished')
                                ->label('Furnished'),
                            Toggle::make('pet_friendly')
                                ->label('Pet Friendly'),
                            Toggle::make('smoking_allowed')
                                ->label('Smoking Allowed'),
                            Toggle::make('is_featured')
                                ->label('Featured Property'),
                        ])->columns(1),

                ])->columnSpan(1),

            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->getStateUsing(function ($record) {
                        return $record->images ? [$record->images[0] ?? null] : [null];
                    })
                    ->placeholder('https://via.placeholder.com/150x150?text=No+Image'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),
                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'house',
                        'info' => 'apartment',
                        'warning' => 'condo',
                        'primary' => 'studio',
                        'secondary' => fn ($state) => in_array($state, ['villa', 'townhouse', 'duplex', 'penthouse']),
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'rented',
                        'warning' => 'maintenance',
                        'secondary' => 'inactive',
                    ]),
                TextColumn::make('full_address')
                    ->label('Location')
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),
                TextColumn::make('bedrooms')
                    ->label('Beds')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('bathrooms')
                    ->label('Baths')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('rent_amount')
                    ->label('Rent')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('rent_period')
                    ->label('Period')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(Property::STATUSES)
                    ->multiple(),
                SelectFilter::make('type')
                    ->options(Property::TYPES)
                    ->multiple(),
                SelectFilter::make('rent_period')
                    ->options(Property::RENT_PERIODS),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Set the owner_id to the current landlord's ID
                        $data['owner_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
                AssociateAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
                Action::make('viewRentals')
                    ->label('Rentals')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->iconButton()
                    ->url(fn ($record): string => '/admin/rentals?' . http_build_query([
                        'tableFilters' => [
                            'property_id' => ['value' => $record->id]
                        ]
                    ]))
                    ->openUrlInNewTab(),
                DissociateAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Properties')
            ->emptyStateDescription('This landlord doesn\'t own any properties yet. Add one using the button above.')
            ->emptyStateIcon('heroicon-o-home');
    }
}
