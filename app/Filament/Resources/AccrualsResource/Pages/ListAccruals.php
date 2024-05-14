<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use Filament\Actions;
use App\Filament\Resources\AccrualsResource\Widgets\AccrualStats;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AccrualsResource;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;

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
    protected function paginateTableQuery(Builder $query): CursorPaginator
{
    return $query->cursorPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
}


}
