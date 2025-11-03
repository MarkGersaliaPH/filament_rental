<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use App\Services\InvoiceGenerationService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateInvoice')
                ->label('Generate Invoice')
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Invoice')
                ->modalDescription('This will create a new invoice for this rental with the current rental details.')
                ->modalSubmitActionLabel('Generate Invoice')
                ->modalWidth(Width::Medium)
                ->action(function () {
                    try {
                        // Generate invoice using the service
                        $invoice = InvoiceGenerationService::generateForRental($this->record, [
                            'include_security_deposit' => false, // You can customize this
                            'payment_status' => $this->record->payment_status, // You can customize this 
                        ]);
                        
                        Notification::make()
                            ->title('Invoice Generated Successfully')
                            ->body('Invoice #' . $invoice->invoice_number . ' has been created and saved.')
                            ->success()
                            ->actions([
                                Action::make('view')
                                    ->label('View Invoice')
                                    ->url($invoice->file_path)
                                    ->openUrlInNewTab()
                            ])
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error Generating Invoice')
                            ->body('Failed to generate invoice: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
                
            Action::make('approve')
                ->label('Approve Rental')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Approve Rental Contract')
                ->modalDescription('Are you sure you want to approve this rental contract? This action will activate the rental and cannot be easily undone.')
                ->modalWidth(Width::Medium)
                ->action(function () {
                    $this->record->update([
                        'status' => 'active',
                        'approved_at' => now(),
                        'approved_by' => Auth::id(),
                    ]);
                    
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
