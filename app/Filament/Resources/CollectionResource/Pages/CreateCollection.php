<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;
    protected static ?string $title = 'Create Collection';
    protected static ?string $breadcrumb = 'Create';
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotification(): ?Notification
    {
            $recipient = auth()->user();
            return Notification::make()
                ->success()
                ->title('UCR Reference ID, Draft Bill No. & Reversal Doc Selected')
                //->body( $this->record->ucr_ref_id . ',' . $this->record->draftbill .' has been selected successfully')
                ->body( $this->record->accruals->ucr_ref_id . ',' .  $this->record->draftbills->draftbill_number . ' and '. $this->record->invoices->accounting_document.' has been selected successfully')
                ->iconColor('success')
                ->duration(5000)
                ->sendToDatabase($recipient);
    }

    // $recipient = auth()->user();
    //         return Notification::make()
    //             ->success()
    //             ->title('UCR Reference ID and Draft Bill No. Selected')
    //             ->body( $this->record->accruals->ucr_ref_id . ', ' .  $this->record->draftbills->draftbill_no . ' has been selected successfully')
    //             ->iconColor('success')
    //             ->duration(5000)
    //             ->sendToDatabase($recipient);

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }
}

