<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\accrual;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AccrualsResource;

class EditAccrualsParkDoc extends EditRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Add Park Documents';
    protected static ?string $breadcrumb = 'Add';
    protected static ?string $slug = 'add';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
        //return $this->getResource()::getUrl('edit-parkdoc', ['record' => $this->getRecord()]);
    }
    protected function getSavedNotification(): ? Notification
    {
        return Notification::make()
            ->success()
            ->title('Park Documents Created')
            ->body('Park Doc No.'. $this->record->UCR_Park_Doc . ' has been created successfully')
            ->iconColor('success')
            ->duration(5000);
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Park Document Added')
            ->body('Park Doc No. '. $this->record->UCR_Park_Doc . ' has been added successfully to ' . $this->record->ucr_ref_id)
            // ->actions([
            //     Action::make('View')
            //         ->button()
            //         ->url('/mli/accruals/' . $this->record->id . '/edit-parkdoc', shouldOpenInNewTab:true)
            //         ->icon('heroicon-o-eye'),
            // ])
            ->sendToDatabase(auth()->user());
    }
}
