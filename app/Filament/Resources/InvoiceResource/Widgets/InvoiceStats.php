<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\accrual;
use App\Models\invoicedetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class InvoiceStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Invoice Created', invoicedetails::count())
                ->description('Total Invoice created')
                ->descriptionIcon('heroicon-o-inbox-stack', IconPosition::Before)
                ->chart([
                    'labels' => invoicedetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => invoicedetails::all()->pluck('amount')->toArray(),
                ])
                ->color('success'),
            Stat::make('Total Invoice Amount', invoicedetails::sum('gr_amount'))
                ->description('Total GR amount')
                ->descriptionIcon('heroicon-o-inbox-stack', IconPosition::Before)
                ->chart([
                    'labels' => invoicedetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => invoicedetails::all()->pluck('gr_amount')->toArray(),
                ])
                ->color('primary'),
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('info'),
        ];
    }
}
