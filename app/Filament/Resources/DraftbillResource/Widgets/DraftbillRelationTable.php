<?php

namespace App\Filament\Resources\DraftbillResource\Widgets;

use Filament\Tables;
use App\Models\accrual;
use Filament\Forms\Get;
use App\Models\draftbill;
use Filament\Tables\Table;
use App\Models\draftbilldetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DraftbillRelationTable extends BaseWidget

{
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    public function table(Table $table): Table

    {

        return $table
            ->query(draftbilldetails::query())
            ->heading('Draft Bill Table')
            ->striped()
            ->columns([
                TextColumn::make('accrual.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbill_number')
                    ->label('Draft Bill No')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('draftbill_amount')
                    ->label('Draft Bill Amount')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->money('Php'),
                TextColumn::make('draftbill_particular')
                    ->label('Draft Bill Particular')
                    ->searchable()
                    ->wrap(4)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('bill_date_created')
                    ->label('Bill Date Created')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->date(),
                TextColumn::make('bill_date_submitted')
                    ->label('Bill Date Submitted')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->date(),
                TextColumn::make('bill_date_approved')
                    ->label('Bill Date Approved')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->date(),
            ]);
    }
}
