<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;


class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
    protected static ?string $title = 'Create Invoice';
    protected static ?string $breadcrumb = 'Create';
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotification(): ?Notification
    {
        $recipient = auth()->user();
            return Notification::make()
                ->success()
                ->title('UCR Reference ID and Draft Bill No. Selected')
                ->body( $this->record->accruals->ucr_ref_id . ' and ' .  $this->record->draftbills->draftbill_number . ' has been selected successfully')
                ->iconColor('success')
                ->duration(5000)
                ->sendToDatabase($recipient)
                ->actions([
                    Action::make('View')
                        ->button()
                        ->url('/mli/invoices/' . $this->record->id . '/edit', shouldOpenInNewTab:true)
                        ->icon('heroicon-o-eye'),
                ]);;

    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }

}
