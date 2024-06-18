<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\accrual;
use App\Models\invoicedetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Number;

class InvoiceStats extends BaseWidget
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
            Stat::make('Invoice Created', invoicedetails::count())
            ->description('Total Invoice Created')
            ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
            ->color('success')
            ->chart([1, 100, invoicedetails::count()]),
            Stat::make('Total Good Receipt Amount', '₱' . $formatNumber(invoicedetails::sum('gr_amount')))
                ->description('Total Good Receipt Amount')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->chart([1, 100000, invoicedetails::sum('gr_amount')])
                ->color('primary'),
                Stat::make('Total Posted Amount', '₱' . $formatNumber(invoicedetails::sum('invoice_posting_amount')))
                ->description('Total Posted Amount')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->chart([1, 100000, invoicedetails::sum('invoice_posting_amount')])
                ->color('primary'),
        ];
    }
}
