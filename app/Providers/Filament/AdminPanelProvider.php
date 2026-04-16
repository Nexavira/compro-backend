<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->brandName('Nexavira')
            ->login()
            ->spa()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Tenants')
                    ->collapsible(true)
                    ->collapsed(),
                
                NavigationGroup::make()
                    ->label('Transactions')
                    ->collapsible(true)
                    ->collapsed(),
                
                NavigationGroup::make()
                    ->label('CMS')
                    ->collapsible(true)
                    ->collapsed(),    
                
                NavigationGroup::make()
                    ->label('Access Control')
                    ->collapsible(true)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('System')
                    ->collapsible(true)
                    ->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // WelcomeWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Teal,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                'panels::styles.after',
                fn (): string => '
                <style>
                    .fi-main-ctn { padding-top: 1.5rem; }
                    
                    aside.fi-sidebar { 
                        background-color: #ffffff !important; 
                        border-right: 1px solid #e2e8f0 !important; 
                    }
                    
                    .fi-ta-ctn, .fi-wi-stats-overview-stat, .fi-wi { 
                        border-radius: 1rem !important;
                        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05) !important; 
                        border: 1px solid #f8fafc !important;
                    }
                    
                    .fi-topbar {
                        background-color: #f1f5f9 !important;
                        border-bottom: 1px solid #e2e8f0 !important;
                    }
                </style>',
            );
    }
}
