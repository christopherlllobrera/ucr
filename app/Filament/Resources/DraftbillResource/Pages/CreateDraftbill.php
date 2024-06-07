<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use App\Models\draftbill;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;

class CreateDraftbill extends CreateRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Create Draft Bill';
    protected static ?string $breadcrumb = 'Create';
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('UCR Reference ID Created')
            ->body( $this->record->accrual->ucr_ref_id . ' has been selected successfully, you can now proceed to select the draft bill details.')
            ->iconColor('success')
            ->duration(5000)
            ->sendToDatabase(auth()->user())
            ->actions([
                Action::make('View Draft Bill')
                    ->button()
                    ->url('/mli/draftbills/' . $this->record->id . '/edit', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-eye'),
            ]);
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }
}
