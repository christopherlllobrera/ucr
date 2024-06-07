<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceStats;
use App\Filament\Resources\DraftbillResource\Widgets\DraftbillRelationTable;
use App\Filament\Resources\InvoiceResource\Widgets\DraftbillAccrualTable;
use App\Filament\Resources\InvoiceResource\Widgets\DraftbillTable;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceAccrualsTable;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;
    protected static ?string $title = 'Invoice';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Invoice'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InvoiceStats::class,
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            //DraftbillAccrualTable::class,
            DraftbillTable::class,
        ];
    }
}
