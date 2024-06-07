<?php

namespace App\Filament\Resources\DraftbillResource\Widgets;

use Filament\Tables;
use App\Models\accrual;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Pages\Page;
use Pages\EditAccruals;
use App\Filament\Resources\AccrualsResource\Pages;


class AccrualsTable extends BaseWidget

{
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    public function table(Table $table): Table

    {
        return $table
            ->query(accrual::query())
            ->heading('Accrual Table')
            ->striped()
            ->columns([
                TextColumn::make('ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->wrap(2)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('accrual_amount')
                    ->label('Accrual Amount')
                    ->money('Php')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_accrued')
                    ->label('Date Accrued in SAP')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('UCR_Park_Doc')
                    ->label('UCR Park Doc No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business_unit')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('period_started')
                    ->label('Period Started')
                    ->searchable()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('period_ended')
                    ->label('Period Ended')
                    ->searchable()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('month')
                    ->label('Month')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('wbs_no')
                    ->label('WBS No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contract_type')
                    ->label('Contract Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('ucr_ref_id', 'desc');
    }
}
