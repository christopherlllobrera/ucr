<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use Filament\Actions;
use App\Models\accrual;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AccrualsResource;

class CreateAccruals extends CreateRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Create Accruals Detail';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $recipient = auth()->user();
        return Notification::make()
            ->success()
            ->title('Accruals Created')
            ->body( $this->record->ucr_ref_id . ' has been created successfully')
            ->iconColor('success')
            ->duration(5000)
            ->actions([
                Action::make('View Accruals')
                    ->button()
                    ->url('/mli/accruals/' . $this->record->id . '/edit', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-eye'),
            ]);
    }
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Accruals Created')
            ->body( $this->record->ucr_ref_id . ' has been created successfully')
            ->actions([
                Action::make('View Accruals')
                    ->button()
                    ->url('/mli/accruals/' . $this->record->id . '/edit', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-eye'),
            ])
            ->sendToDatabase(auth()->user());
    }
}
