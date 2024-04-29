<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccruals extends CreateRecord
{
    protected static string $resource = AccrualsResource::class;

    protected static ?string $title = 'Create Accruals Detail';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Accruals created successfully';
    }
}
