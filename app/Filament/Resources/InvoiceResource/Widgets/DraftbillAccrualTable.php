<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use Filament\Tables;
use App\Models\draftbill;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\TableWidget as BaseWidget;

class DraftbillAccrualTable extends BaseWidget
{
    protected static bool $isLazy = false;
    public function table(Table $table): Table
    {
        return $table
            ->query(draftbill::query())
            ->heading('Accruals Table')
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                // TextColumn::make('draftbill_details.draftbill.no')
                //     ->label('Draft Bill No.')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('accruals.client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->searchable()
                    ->sortable()
                    ->money('Php'),
                TextColumn::make('accruals.period_started')
                    ->label('Period Started')
                    ->searchable()
                    ->date()
                    ->sortable(),
                TextColumn::make('accruals.period_ended')
                    ->label('Period Ended')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.wbs_no')
                    ->label('WBS No.')
                    ->toggleable (isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
