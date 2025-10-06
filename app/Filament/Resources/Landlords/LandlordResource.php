<?php

namespace App\Filament\Resources\Landlords;

use App\Filament\Resources\Landlords\Pages\CreateLandlord;
use App\Filament\Resources\Landlords\Pages\EditLandlord;
use App\Filament\Resources\Landlords\Pages\ListLandlords;
use App\Filament\Resources\Landlords\Pages\ViewLandlord;
use App\Filament\Resources\Landlords\RelationManagers\PropertiesRelationManager;
use App\Filament\Resources\Landlords\Schemas\LandlordForm;
use App\Filament\Resources\Landlords\Schemas\LandlordInfolist;
use App\Filament\Resources\Landlords\Tables\LandlordsTable;
use App\Models\Landlord;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LandlordResource extends Resource
{
    protected static ?string $model = Landlord::class;

    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    
    protected static string|UnitEnum|null $navigationGroup = 'Rental Management';
    public static function form(Schema $schema): Schema
    {
        return LandlordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
     
         return $schema
        ->components([
            TextEntry::make('name'),
            TextEntry::make('email'),
            TextEntry::make('notes')
                ->columnSpanFull(),
        ]);;
    }

    public static function table(Table $table): Table
    {
        return LandlordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandlords::route('/'),
            'create' => CreateLandlord::route('/create'),
            // 'view' => ViewLandlord::route('/{record}'),
            'edit' => EditLandlord::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
