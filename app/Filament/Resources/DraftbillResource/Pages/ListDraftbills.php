<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDraftbills extends ListRecords
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Draft bills';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Select UCR Reference ID'),

        ];
    }
}
