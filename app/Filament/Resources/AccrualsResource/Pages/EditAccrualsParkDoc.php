<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccrualsParkDoc extends EditRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Add Park Documents';
    protected static ?string $breadcrumb = 'Add';
    protected static ?string $slug = 'add';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getUpdateNotificationTitle(): ?string
    {
        return 'Park Documents updated successfully';
    }



}
