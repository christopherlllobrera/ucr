<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use Filament\Actions;
use App\Filament\Resources\AccrualsResource\Widgets\AccrualStats;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AccrualsResource;

class ListAccruals extends ListRecords
{
    protected static string $resource = AccrualsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Accruals'),
        ];
    }

    protected function getHeaderWidgets():array
    {
        return [
            AccrualStats::class,
        ];
    }


}
