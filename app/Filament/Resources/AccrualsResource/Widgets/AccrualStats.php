<?php

namespace App\Filament\Resources\AccrualsResource\Widgets;

use App\Models\accrual;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccrualStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Accruals Created', accrual::count()),
            Stat::make('Total Accrued', accrual::sum('accrual_amount')),
        ];
    }
}
