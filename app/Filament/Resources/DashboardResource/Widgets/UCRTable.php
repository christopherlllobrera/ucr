<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Filament\Resources\AccrualsResource;
use App\Models\accrual;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UCRTable extends BaseWidget
{
    //protected static ?string $title = 'UCR Table';

    public function table(Table $table): Table
    {
        return $table
            ->query(accrual::query())
            ->defaultSort('client_name', 'asc')
            ->columns([
                TextColumn::make('client_name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
