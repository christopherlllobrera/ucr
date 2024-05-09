<?php

namespace App\Filament\Resources\DraftbillResource\Widgets;

use Shmop;
use App\Models\accrual;
use App\Models\draftbill;
use App\Models\draftbilldetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Number;

class DraftbillStats extends BaseWidget
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
            Stat::make('Draftbills Created', draftbilldetails::count())
                ->description('Total Draft bill created')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('success')
                ->chart([1, 100, 500, 800, 900, 1000, draftbilldetails::count()]),
            Stat::make('Total Draftbills Amount','₱'. $formatNumber (draftbilldetails::sum('draftbill_amount')))
                ->description('Total Draft bill amount')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before)
                ->color('primary')
                ->chart([1,500000, draftbilldetails::sum('draftbill_amount')]),
                Stat::make('Pending Draft Bill', (accrual::count() - draftbilldetails::count()) )
                ->description('Total Pending Draft Bill')
                ->descriptionIcon('heroicon-o-arrow-trending-down', IconPosition::After)
                ->color('danger')
                ->chart([1000, 500, 300, 200, 100, (accrual::count() - draftbilldetails::count()) ]),

        ];
    }
}
