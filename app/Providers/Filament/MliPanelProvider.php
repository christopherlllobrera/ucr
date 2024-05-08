<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AccrualsResource\Widgets\AccrualStats;
use App\Filament\Resources\DashboardResource\Widgets\UCRStats;
use App\Models\accrual;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\FontProviders\GoogleFontProvider;

class MliPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('mli')
            ->path('mli')
            ->login()
            ->colors(['primary' => Color::Orange,])
            ->favicon('images/favicon.ico')
            ->font('Inter', provider: GoogleFontProvider::class)
            ->brandLogo(fn() => view('filament.app.logo'))
            //->brandLogo('images/MLI (1).svg')
            ->brandLogoHeight('60px')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->sidebarWidth('15rem')

            //->sidebarFullyCollapsibleOnDesktop(false)
            //->sidebarCollapsibleOnDesktop(true)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                    UCRStats::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
