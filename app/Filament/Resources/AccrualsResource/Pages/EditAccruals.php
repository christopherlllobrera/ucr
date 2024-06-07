<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;

class EditAccruals extends EditRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Edit Accruals';
    protected static ?string $breadcrumb = 'Edit';
    protected static ?string $slug = 'adds';
    protected static ?string $recordTitleAttribute = 'ucr_ref_id';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
             ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Accruals Deleted')
                    ->body( $this->record->ucr_ref_id . ' has been deleted successfully')
                    ->iconColor('success')
                    ->duration(5000)
                    //->sendToDatabase(auth()->user())
                ),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        $recipient = auth()->user();
        return Notification::make()
            ->success()
            ->title('Accruals Updated')
            ->body( $this->record->ucr_ref_id . ' has been updated successfully')
            ->iconColor('success')
            ->duration(5000)
            ->sendToDatabase($recipient)
            ->actions([
                Action::make('View Changes')
                    ->button()
                    ->url('/mli/accruals/' . $this->record->id . '/edit', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-eye'),
            ])
            ->persistent();
    }
}
