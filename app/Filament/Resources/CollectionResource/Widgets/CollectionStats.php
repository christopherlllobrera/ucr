<?php

namespace App\Filament\Resources\CollectionResource\Widgets;

use App\Models\accrual;
use App\Models\collection;
use App\Models\invoicedetails;
use Illuminate\Support\Number;
use App\Models\collectiondetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CollectionStats extends BaseWidget
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
            Stat::make('Collected Created', collectiondetails::count())
                ->description('Total Collected created')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('success')
                ->chart([1, 2, collectiondetails::count()]),
                Stat::make('Total Amount Collected', '₱' . $formatNumber(collectiondetails::sum('amount_collected')))
                ->description('Total amount collected')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('primary')
                ->chart([1, 2, collectiondetails::sum('amount_collected')]),
                Stat::make('Invoice Created', invoicedetails::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('success')
                ->chart([1, 2, invoicedetails::count()]),
        ];
    }
}
