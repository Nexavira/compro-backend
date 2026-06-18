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
            ->maxContentWidth('full')
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
                    /* Mengubah background color di semua halaman menjadi abu-abu agak gelap */
                    body, .fi-layout, .fi-main {
                        background-color: #e2e8f0 !important; 
                    }

                    .fi-main-ctn { padding-top: 1.5rem; }
                    
                    aside.fi-sidebar { 
                        background-color: #ffffff !important; 
                        border-right: 1px solid #cbd5e1 !important; 
                    }
                    
                    /* Mengurangi ketebalan border input menjadi 1px */
                    .fi-input-wrp {
                        border: 1px solid #cbd5e1 !important; 
                        border-radius: 0.5rem !important;
                    }
                    
                    /* Mengurangi ketebalan border builder / repeater items */
                    /* Mengubah background builder/repeater items menjadi agak gelap (soft grey) agar terpisah */
                    .fi-fo-builder-item, 
                    .fi-fo-repeater-item {
                        border: 1px solid #cbd5e1 !important;
                        border-radius: 0.75rem !important;
                        background-color: #f8fafc !important; /* Soft grey background */
                    }

                    /* Mengurangi ketebalan border header item builder / repeater */
                    .fi-fo-builder-item-header,
                    .fi-fo-repeater-item-header {
                        border-bottom: 1px solid #cbd5e1 !important;
                        background-color: #f1f5f9 !important; /* Header builder agak gelap */
                    }
                    
                    /* Mengurangi ketebalan border section menjadi 1px */
                    .fi-section {
                        border: 1px solid #cbd5e1 !important;
                        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
                        background-color: #ffffff !important; /* Section utama tetap putih */
                    }

                    /* Mengurangi ketebalan border fieldset menjadi 1px */
                    /* Mengubah background fieldset menjadi agak gelap agar terpisah */
                    fieldset.fi-fo-fieldset, .fi-fo-fieldset, fieldset {
                        border: 1px solid #cbd5e1 !important;
                        border-radius: 0.75rem !important;
                        background-color: #f8fafc !important; /* Soft grey background */
                    }
                    
                    /* Mengurangi ketebalan border table, widget, dan card statistik menjadi 1px */
                    .fi-ta-ctn, .fi-wi-stats-overview-stat, .fi-wi { 
                        border-radius: 1rem !important;
                        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05) !important; 
                        border: 1px solid #cbd5e1 !important;
                    }
                    
                    .fi-topbar {
                        background-color: #f1f5f9 !important;
                        border-bottom: 1px solid #cbd5e1 !important;
                    }
                </style>',
            );
    }
}
