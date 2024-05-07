<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;
    protected static ?string $title = 'Invoice';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->hidden(),
            $this->getCancelFormAction()->hidden(),
        ];
    }
}
