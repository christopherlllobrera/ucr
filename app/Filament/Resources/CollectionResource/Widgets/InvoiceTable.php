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
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table

    {
        return $table
            ->query(invoice::query())
            ->heading('Invoice Table')
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TextColumn::make('invoicerelation.invoice_posting_amount')
                    ->label('Invoice Posting Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbills.draftbill_number')
                    ->label('Draftbill No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoicerelation.reversal_doc')
                    ->label('Reversal Doc.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('invoicerelation.accounting_document')
                    ->label('Accounting Doc.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('invoicerelation.billing_statement')
                    ->label('Billing Statement')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
