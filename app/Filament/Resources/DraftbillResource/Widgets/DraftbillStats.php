<?php

namespace App\Filament\Resources\DraftbillResource\Widgets;

use Shmop;
use App\Models\accrual;
use App\Models\draftbill;
use App\Models\draftbilldetails;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DraftbillStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Draftbills Created', draftbilldetails::count())
                ->description('Total Draft bill created')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before)
                ->chart([
                    'labels' => draftbilldetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => draftbilldetails::all()->pluck('amount')->toArray(),
                ])
                ->color('success'),
            Stat::make('Total Draftbills Amount', draftbilldetails::sum('draftbill_amount'))
                ->description('Total Draft bill amount')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before)
                ->chart([
                    'labels' => draftbilldetails::all()->pluck('created_at')->map(fn ($date) => $date->format('M d'))->toArray(),
                    'values' => draftbilldetails::all()->pluck('draftbill_amount')->toArray(),
                ])
                ->color('primary'),
            Stat::make('Accruals Created', accrual::count())
                ->description('Total Accrual created')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('info'),
        ];
    }
}
