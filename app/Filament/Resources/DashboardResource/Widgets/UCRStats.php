<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\accrual;
use App\Models\invoicedetails;
use App\Models\draftbilldetails;
use App\Models\collectiondetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UCRStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = '3';


    protected function getStats(): array
    {
        return [
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition:: Before)
                ->color('success'),
            Stat::make('Draftbills Created', draftbilldetails::count())
                ->description('Total Draft bill created')
                ->descriptionIcon('heroicon-o-wallet', IconPosition:: Before)
                ->chart([
                    'labels' => draftbilldetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => draftbilldetails::all()->pluck('amount')->toArray(),
                ])
                ->color('success'),
            Stat::make('Total Accrued', accrual::sum('accrual_amount'))
                ->description('Total Accrual Amount')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition:: Before)
                ->color('info'),
            Stat::make('Invoice Created', invoicedetails::count())
                ->description('Total Invoice created')
                ->descriptionIcon('heroicon-o-inbox-stack', IconPosition:: Before)
                ->chart([
                    'labels' => invoicedetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => invoicedetails::all()->pluck('amount')->toArray(),
                ])
                ->color('success'),
                Stat::make('Collection Created', collectiondetails::count())
                ->description('Total Collection created')
                ->descriptionIcon('heroicon-o-credit-card', IconPosition:: Before)
                ->chart([
                    'labels' => collectiondetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => collectiondetails::all()->pluck('amount_collected')->toArray(),
                ])
                ->color('success'),

        ];
    }
}
