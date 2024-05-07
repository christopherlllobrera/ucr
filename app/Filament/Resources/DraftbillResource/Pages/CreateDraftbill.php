<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

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
            ->title('UCR Reference ID Selected')
            ->body('Create your draft bill details.');
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }
}
