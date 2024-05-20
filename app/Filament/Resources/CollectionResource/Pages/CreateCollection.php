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
        return Notification::make()
            ->success()
            ->title('UCR Reference ID, Draft Bill No. & Reversal Doc Selected')
            ->body('You can now add collection details.');
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Select'),
            $this->getCancelFormAction(),
        ];
    }
}

