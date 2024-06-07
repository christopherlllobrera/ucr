<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\accrual;
use Filament\Forms\Get;
use App\Models\draftbill;
use App\Models\draftbilldetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;

class DraftbillTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    public function table(Table $table): Table
    {
        return $table
            ->query(draftbill::query())
            ->heading('Draft Bill Table')
            ->striped()
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc.')
                    ->searchable()
                    ->sortable()
                    ->toggleable (isToggledHiddenByDefault: false),
                TextColumn::make('draft.draftbill_number')
                    ->label('Draft Bill No.')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->searchable()
                    ->sortable()
                    ->money('Php'),
                TextColumn::make('draft.draftbill_amount')
                    ->label('Draft Bill Amount.')
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
                TextColumn::make('draft.draftbill_particular')
                    ->label('Particular')
                    ->searchable()
                    ->sortable()
                    ->wrap(2)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc');
    }
}
