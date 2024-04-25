<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDraftbill extends EditRecord
{
    protected static string $resource = DraftbillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
