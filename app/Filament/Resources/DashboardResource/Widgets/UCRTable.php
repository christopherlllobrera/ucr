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

    // public function table(Table $table): Table
    // {

    //     return $table
    //         ->heading('UCR')
    //         ->query(accrual::query()->where('client_name','ATIMONAN ONE ENERGY  INC.')->sum('accrual_amount'))
    //         ->defaultSort('client_name', 'asc')
    //         ->columns([
    //             TextColumn::make('client_name')
    //                 ->label('Client Name'),
    //             TextColumn::make('accrual_amount')

    //         ]);
    // }
}


// [
//     'ATIMONAN ONE ENERGY  INC.',
//     'MERALCO',
//     'MIDC',
//     'MIESCOR',
//     'MSERV',
//     'MSERV (MERALCO ENERGY)',
//     'MSPECTRUM',
//     'MVP',
//     'MWCI (MANILA WATER COMPANY INC.)',
//     'MOVEM',
//     'NLEX CORPORATION',
//     'OCEANA',
//     'PDRF',
//     'RADIUS TELECOM INC.',
//     'ROBINSONS GROUP',
//     'SHIN CLARK POWER CORPORATION',
//     'SM RETAIL  INC.',
//     'TAKASAGO INTERNATIONAL (PHILIPPINES) INC.',
//     'TEH HSIN ENTERPRISE PHIL. CORPORATION',
// ]
