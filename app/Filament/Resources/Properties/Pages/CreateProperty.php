<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    // protected function getHeaderActions(): array
    // {
    //      return [
    //         $this->getCreateFormAction(),
    //         ...($this->canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
    //         $this->getCancelFormAction(),
    //     ];
    // }
    // // remove the default footer actions (Create / Cancel)
    protected function getFormActions(): array
    {
        return [];
    }
}
