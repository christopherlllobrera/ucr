<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
    protected static ?string $title = 'Create Invoice';
    protected static ?string $breadcrumb = 'Create';
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('UCR Reference ID & Draft Bill No. Selected')
            ->body('You can now add invoice details.');
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }

}
