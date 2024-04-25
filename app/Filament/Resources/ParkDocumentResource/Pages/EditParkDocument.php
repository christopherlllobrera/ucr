<?php

namespace App\Filament\Resources\ParkDocumentResource\Pages;

use App\Filament\Resources\ParkDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParkDocument extends EditRecord
{
    protected static string $resource = ParkDocumentResource::class;
    protected static ?string $title = 'Edit Park Document';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
