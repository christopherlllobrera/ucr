<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use Filament\Actions;
use App\Models\draftbill;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DraftbillResource;
use App\Filament\Resources\DraftbillResource\Widgets\AccrualsTable;
use App\Filament\Resources\DraftbillResource\Widgets\DraftbillStats;
use App\Filament\Resources\DraftbillResource\Widgets\DraftbillRelationTable;
use App\Filament\Resources\DraftbillResource\RelationManagers\DraftRelationManager;

class ListDraftbills extends ListRecords
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Draft bills';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Draftbill'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            DraftbillStats::class,
            AccrualsTable::class,
            DraftbillRelationTable::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            DraftRelationManager::class,
        ];
    }
}
