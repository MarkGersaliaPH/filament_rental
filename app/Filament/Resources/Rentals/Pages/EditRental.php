<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve Rental')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Approve Rental Contract')
                ->modalDescription('Are you sure you want to approve this rental contract? This action will activate the rental and cannot be easily undone.')
                // ->modalWidth(MaxWidth::Medium)
                ->modalWidth(Width::Medium)
                ->action(function () {
                    $this->record->update([
                        'status' => 'active',
                        'approved_at' => now(),
                        'approved_by' => Auth::id(),
                    ]);
                    
                    // $this->notify('success', 'Rental contract has been approved successfully.');
                    
                    // Refresh the form to show updated status
                    $this->refreshFormData([
                        'status',
                        'approved_at', 
                        'approved_by'
                    ]);
                }),
            DeleteAction::make(),
        ];
    }
}
