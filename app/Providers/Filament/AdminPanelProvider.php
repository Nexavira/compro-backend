<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Filament\Widgets\WelcomeBannerWidget;
use App\Filament\Widgets\CustomStatsWidget;
use App\Filament\Widgets\ClientTrendChartWidget;
use App\Filament\Widgets\IncomeTrendChartWidget;
use App\Filament\Widgets\LatestActivityWidget;
use App\Filament\Pages\Auth\CustomLogin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->login(CustomLogin::class)
            ->spa()
            ->maxContentWidth('full')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->navigationGroups([

                NavigationGroup::make('Manajemen Klien')
                    ->icon('heroicon-o-building-office-2')
                    ->collapsed(),
                NavigationGroup::make('Transaksi')
                    ->icon('heroicon-o-banknotes')
                    ->collapsed(),
                NavigationGroup::make('Sistem Konten')
                    ->icon('heroicon-o-document-text')
                    ->collapsed(),
                NavigationGroup::make('Kontrol Akses')
                    ->icon('heroicon-o-shield-check')
                    ->collapsed(),
                NavigationGroup::make('Sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                WelcomeBannerWidget::class,
                CustomStatsWidget::class,
                ClientTrendChartWidget::class,
                IncomeTrendChartWidget::class,
                LatestActivityWidget::class,
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
            ])
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                'panels::sidebar.nav.start',
                fn () => view('filament.logo')
            )
            ->renderHook(
                'panels::sidebar.footer',
                fn () => view('filament.sidebar-footer')
            )
            ->renderHook(
                'panels::global-search.after',
                fn () => view('filament.topbar-icons')
            )
            ->renderHook(
                'panels::styles.after',
                fn (): string => '
                <style>
                    /* Mengubah background color di semua halaman menjadi abu-abu terang */
                    body, .fi-layout, .fi-main {
                        background-color: #f8fafc !important; 
                    }

                    /* OVERRIDE LAYOUT DESKTOP: Sidebar Full Height, Topbar di Kanan */
                    @media (min-width: 1024px) {
                        body {
                            display: flex !important;
                            flex-direction: column !important;
                        }
                        .fi-layout {
                            position: static !important;
                        }
                        aside.fi-sidebar {
                            position: fixed !important;
                            top: 0 !important;
                            left: 0 !important;
                            height: 100vh !important;
                            z-index: 30 !important;
                            margin-top: 0 !important;
                            transition: width 0.3s ease !important;
                        }
                        .fi-topbar {
                            position: fixed !important;
                            top: 0 !important;
                            right: 0 !important;
                            z-index: 20 !important;
                            border-bottom: 1px solid #e5e7eb !important;
                            transition: width 0.3s ease !important;
                        }

                        /* DEFAULT: KETIKA MINIMIZED (Collapsed) */
                        .fi-main-ctn {
                            margin-left: 5rem !important; /* Lebar sidebar saat collapse */
                            padding-top: 4rem !important; /* Tinggi Topbar */
                            transition: margin-left 0.3s ease !important;
                        }
                        .fi-topbar {
                            width: calc(100% - 5rem) !important;
                        }
                        aside.fi-sidebar {
                            width: 5rem !important;
                        }

                        /* Perapian Logo dan Footer saat collapsed (Minimize) */
                        body:not(:has(.fi-main-ctn-sidebar-open)) .logo-container {
                            justify-content: center !important;
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                        }
                        body:not(:has(.fi-main-ctn-sidebar-open)) .footer-container {
                            justify-content: center !important;
                            padding: 0 !important;
                            background-color: transparent !important;
                            border: none !important;
                        }

                        /* Sembunyikan teks nama aplikasi & profil user saat collapsed */
                        body:not(:has(.fi-main-ctn-sidebar-open)) .logo-text,
                        body:not(:has(.fi-main-ctn-sidebar-open)) .footer-text {
                            display: none !important;
                        }
                        body:not(:has(.fi-main-ctn-sidebar-open)) .fi-sidebar-header {
                            padding: 0 !important;
                            justify-content: center;
                        }

                        /* STATE: KETIKA TERBUKA (Expanded) */
                        body:has(.fi-main-ctn-sidebar-open) .fi-main-ctn {
                            margin-left: 16rem !important; /* Lebar default sidebar */
                        }
                        body:has(.fi-main-ctn-sidebar-open) .fi-topbar {
                            width: calc(100% - 16rem) !important;
                        }
                        body:has(.fi-main-ctn-sidebar-open) aside.fi-sidebar {
                            width: 16rem !important;
                        }
                    }

                    /* Sembunyikan logo bawaan Filament dan avatar di topbar */
                    .fi-topbar .fi-logo, .fi-sidebar-header .fi-logo, .fi-topbar .fi-user-menu { display: none !important; }

                    /* Pindahkan Global Search ke Kiri secara paksa menggunakan Flex Order */
                    .fi-topbar nav > div:last-child,
                    .fi-topbar .ms-auto {
                        margin-left: 0 !important;
                        flex: 1;
                        display: flex;
                        justify-content: space-between;
                    }
                    .fi-global-search-field, .fi-global-search, .fi-topbar-search, [role="search"] {
                        order: -1 !important;
                        margin-right: auto !important;
                        min-width: 250px;
                    }

                    /* Ganti icon Chevron jadi Hamburger di Desktop */
                    .fi-sidebar-collapse-btn svg,
                    .fi-layout-sidebar-toggle-btn svg {
                        display: none !important;
                    }
                    .fi-sidebar-collapse-btn::before,
                    .fi-layout-sidebar-toggle-btn::before {
                        content: url("data:image/svg+xml;utf8,<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%23374151\' stroke-width=\'2\' width=\'24\' height=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 6h16M4 12h16M4 18h16\'></path></svg>");
                        display: block;
                        width: 24px;
                        height: 24px;
                    }

                    /* TEMA PRIMARY SIDEBAR (DARK EMERALD) */
                    aside.fi-sidebar { 
                        background-color: #225d48 !important; 
                        border-right: none !important; 
                    }
                    /* Hapus background header sidebar agar menyatu dengan warna hijau pekat */
                    .fi-sidebar-header {
                        background-color: transparent !important;
                        box-shadow: none !important;
                        height: auto !important;
                        padding: 0 !important;
                    }
                    /* Styling Custom Scrollbar untuk Sidebar */
                    aside.fi-sidebar ::-webkit-scrollbar {
                        width: 4px;
                    }
                    aside.fi-sidebar ::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    aside.fi-sidebar ::-webkit-scrollbar-thumb {
                        background: rgba(255, 255, 255, 0.2);
                        border-radius: 10px;
                    }
                    aside.fi-sidebar ::-webkit-scrollbar-thumb:hover {
                        background: rgba(255, 255, 255, 0.3);
                    }

                    /* Mengubah warna teks menu sidebar menjadi putih transparan */
                    .fi-sidebar-item-label, .fi-sidebar-item-icon {
                        color: rgba(255, 255, 255, 0.8) !important;
                    }
                    .fi-sidebar-group-label,
                    .fi-sidebar-group-btn svg,
                    .fi-sidebar-group-icon {
                        color: rgba(255, 255, 255, 0.8) !important;
                    }

                    /* Tampilan menu hover */
                    .fi-sidebar-item-btn:hover {
                        background-color: rgba(255, 255, 255, 0.05) !important;
                    }

                    /* Tampilan menu aktif (garis tepi hijau terang & background sangat tipis) */
                    li.fi-active > .fi-sidebar-item-btn {
                        background-color: rgba(255, 255, 255, 0.95) !important; /* Sengaja dibuat hampir putih solid agar kontras */
                        border-radius: 0 !important;
                        border-top-right-radius: 0.5rem !important;
                        border-bottom-right-radius: 0.5rem !important;
                        border-left: 4px solid #34d399 !important; /* Hijau terang */
                        margin-left: -0.5rem !important;
                        padding-left: calc(0.75rem - 4px) !important; /* Sesuaikan padding agar icon tidak bergeser */
                    }

                    /* Warna Teks & Icon Menu Aktif diubah menjadi Emerald pekat agar terbaca di atas background terang */
                    li.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
                    li.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
                        color: #047857 !important; /* Emerald 700 */
                        font-weight: 800 !important;
                    }
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

                    /* ---------------------------------
                       TEMA DROPDOWN & LIST
                    ---------------------------------- */
                    .fi-dropdown-panel {
                        border: 1px solid #e2e8f0 !important;
                        border-radius: 0.75rem !important;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                        overflow: hidden !important;
                    }
                    .fi-dropdown-list-item {
                        transition: all 0.2s ease-in-out !important;
                        border-radius: 0.5rem !important;
                        margin: 2px 4px !important;
                    }
                    .fi-dropdown-list-item:hover {
                        background-color: #ecfdf5 !important; /* Emerald 50 */
                        color: #047857 !important; /* Emerald 700 */
                    }
                    .fi-dropdown-list-item:hover .fi-dropdown-list-item-icon {
                        color: #059669 !important; /* Emerald 600 */
                    }

                    /* ---------------------------------
                       TEMA BUTTON & ACTION
                    ---------------------------------- */
                    /* Tombol Utama (Primary) */
                    .fi-btn-color-primary {
                        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
                        border: none !important;
                        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2), 0 2px 4px -1px rgba(5, 150, 105, 0.1) !important;
                        transition: all 0.3s ease !important;
                        border-radius: 0.5rem !important;
                    }
                    .fi-btn-color-primary:hover {
                        transform: translateY(-1px) !important;
                        box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3), 0 4px 6px -2px rgba(5, 150, 105, 0.2) !important;
                        filter: brightness(1.1) !important;
                    }

                    /* Tombol Ikon & Aksi Biasa (Secondary/Gray) */
                    .fi-btn-color-gray, .fi-icon-btn, .fi-ac-btn {
                        transition: all 0.2s ease !important;
                        border-radius: 0.5rem !important;
                    }
                    .fi-btn-color-gray:hover, .fi-icon-btn:hover {
                        background-color: #f1f5f9 !important;
                        color: #0f172a !important;
                        transform: translateY(-1px) !important;
                        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
                    }

                    /* Mengurangi ketebalan border fieldset menjadi 1px */
                    /* Mengubah background fieldset menjadi agak gelap agar terpisah */
                    fieldset.fi-fo-fieldset, .fi-fo-fieldset, fieldset {
                        border: 1px solid #cbd5e1 !important;
                        border-radius: 0.75rem !important;
                        background-color: #f8fafc !important; /* Soft grey background */
                    }

                    /* Mengurangi ketebalan border table, widget, dan card statistik menjadi 1px */
                    .fi-ta-ctn, .fi-wi-stats-overview-stat { 
                        border-radius: 1rem !important;
                        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05) !important; 
                        border: 1px solid #e2e8f0 !important;
                        background-color: #ffffff !important;
                    }

                    .fi-topbar {
                        background-color: #ffffff !important;
                        border-bottom: 1px solid #e2e8f0 !important;
                    }
                </style>',
            );
    }
}
