<?php

namespace App\Filament\Resources\AccrualsResource\Widgets;

use App\Models\accrual;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AccrualStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('success'),
            Stat::make('Total Accrued', accrual::sum('accrual_amount'))
                ->description('Total Accrual Amount')
                ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::Before)
                ->color('primary'),
            Stat::make('Total UCR Park Doc', accrual::where('ucr_park_doc')->count())
                ->description('Total UCR Park Doc')
                ->descriptionIcon('heroicon-o-document-plus', IconPosition::Before)
                ->color('info'),
        ];
    }
}
