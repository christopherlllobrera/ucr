<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccruals extends EditRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Edit Accruals';
    protected static ?string $breadcrumb = 'Edit';
    protected static ?string $slug = 'add';
    protected static ?string $recordTitleAttribute = 'ucr_ref_id';

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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Accruals updated successfully';
    }
}
