<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Filament\Resources\AccrualsResource;
use App\Models\accrual;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
class UCRTable extends BaseWidget
{
    //protected static ?string $title = 'UCR Table';

    public function table(Table $table): Table
    {

        return $table
            ->heading('UCR')
            ->query(accrual::query()->where('client_name', 'Meralco'))
            ->defaultSort('client_name', 'asc')
            ->columns([
                TextColumn::make('client_name')
                    ->label('Client Name'),

            ]);
    }
}
