<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CollectionResource;
use App\Filament\Resources\CollectionResource\Widgets\CollectionStats;

class ListCollections extends ListRecords
{
    protected static string $resource = CollectionResource::class;
    protected static ?string $title = 'Collection';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Collection'),

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [

            CollectionStats::class,

        ];
    }
}
