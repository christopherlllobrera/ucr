<?php

namespace App\Filament\Resources\CollectionResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\invoicedetails;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\TableWidget as BaseWidget;

class InvoiceDetailsTable extends BaseWidget
{
    protected static bool $isLazy = false;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                invoicedetails::query()
            )
            ->heading('Invoice Table')
            ->columns([
                Tables\Columns\TextColumn::make('reversal_doc')
                    ->label('Reversal Document')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                Tables\Columns\TextColumn::make('accounting_doc')
                    ->label('Accounting Document')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gr_amount')
                    ->label('GR Amount')
                    ->searchable()
                    ->sortable()
                    ->money('PHP')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('gr_no_meralco')
                    ->label('GR No. Created by Meralco')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pojo_no')
                    ->label('PO/JO No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('billing_statement')
                    ->label('Billing Statement')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_posting_amount')
                    ->label('Posted Amount')
                    ->searchable()
                    ->sortable()
                    ->money('PHP')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('invoice_posting_date')
                    ->label('Posting Date')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
