<?php

namespace App\Filament\Resources\CollectionResource\Widgets;

use App\Models\accrual;
use App\Models\collection;
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
        return [
            Stat::make('Collected Created', collectiondetails::count())
                ->description('Total Collected created')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before)
                ->chart([
                    'labels' => collectiondetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => collectiondetails::all()->pluck('amount_collected')->toArray(),
                ])
                ->color('success'),
            Stat::make('Total Amount Collected', collectiondetails::sum('amount_collected'))
                ->description('Total amount collected')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before)
                ->chart([
                    'labels' => collectiondetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => collectiondetails::all()->pluck('amount_collected')->toArray(),
                ])
                ->color('primary'),
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('info'),
        ];
    }
}
