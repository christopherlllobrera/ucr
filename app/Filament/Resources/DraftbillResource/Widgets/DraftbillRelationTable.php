<?php

namespace App\Filament\Resources\DraftbillResource\Widgets;

use App\Models\draftbilldetails;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DraftbillRelationTable extends BaseWidget

{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query( draftbilldetails::query())
            ->heading('Draftbill Details')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID'),
                Tables\Columns\TextColumn::make('draftbill_no')
                ->label('Draftbill No.')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('draftbill_amount')
                ->label('Draftbill Amount')
                ->searchable()
                ->sortable()
                ->money('Php'),
                Tables\Columns\TextColumn::make('draftbill_particular'),
                Tables\Columns\TextColumn::make('bill_date_created')
                ->label('Date Created')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bill_date_submitted')
                ->label('Date Submitted')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bill_date_approved')
                ->label('Date Approved')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
