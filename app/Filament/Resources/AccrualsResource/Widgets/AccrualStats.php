<?php

namespace App\Filament\Resources\AccrualsResource\Widgets;

use App\Models\accrual;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Number;

class AccrualStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }
            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'K';
            }
            if($number < 1000000000){
                return Number::format($number / 1000000, 2) . 'M';
            }
            return Number::format($number / 1000000000, 2) . 'B';
        };
        return [
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('success')
                ->chart([1, 100, 500, 800, 900, 1000, accrual::count()]),
            Stat::make('Total Accrued', '₱' . $formatNumber (accrual::sum('accrual_amount')))
                ->description('Total Accrual Amount')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('primary')
                ->chart([1,5000000 , accrual::sum('accrual_amount')]),
        ];
    }
}
