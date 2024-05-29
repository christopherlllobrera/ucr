<?php

namespace App\Filament\Resources\CollectionResource\Widgets;

use Filament\Tables;
use App\Models\invoice;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\TableWidget as BaseWidget;

class InvoiceTable extends BaseWidget
{
    protected static bool $isLazy = false;
    public function table(Table $table): Table

    {
        return $table
            ->query(invoice::query())
            ->heading('Accruals Table')
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('draftbills.draftbill_no')
                    ->label('Draftbill No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbills.draftbill_amount')
                    ->label('Draftbill Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
