<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;
    protected static ?string $title = 'Collection';
    protected static ?string $breadcrumb = 'Add Collection';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->hidden(),
            $this->getCancelFormAction()->hidden(),
        ];
    }
}
