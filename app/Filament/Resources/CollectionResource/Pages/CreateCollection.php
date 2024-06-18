<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Notifications\Actions\Action;
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
        return Notification::make()
            ->success()
            ->title('UCR Reference ID, Draft Bill No. & Reversal Doc Selected')
            //->body( $this->record->ucr_ref_id . ',' . $this->record->draftbill .' has been selected successfully')
            ->body($this->record->accruals->ucr_ref_id.','.$this->record->draftbills->draftbill_number.' and '.$this->record->invoices->accounting_document.' has been selected successfully')
            ->iconColor('success')
            ->duration(5000)
            ->actions([
                Action::make('View')
                    ->button()
                    ->url('/mli/collections/'.$this->record->id.'/edit', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-eye'),
            ]);

    }
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Collection Alert')
            ->body($this->record->accruals->ucr_ref_id.','.$this->record->draftbills->draftbill_number.' and '.$this->record->invoices->accounting_document.' has been selected successfully you can now proceed in collection details.')
            ->actions([
                Action::make('View collection')
                    ->button()
                    ->url('/mli/draftbills/'. $this->record->id . '/edit', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-eye'),
            ])
            ->sendToDatabase(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }
}
