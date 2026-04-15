<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Klien Aktif', '12')
                ->description('Meningkat 3 dari bulan lalu')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 5, 4, 7, 6, 10, 12])
                ->color('success'),

            Stat::make('Jumlah Pengguna', '145')
                ->description('Total dari seluruh tenant')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp 24.500.000')
                ->description('Pendapatan bulan berjalan (MRR)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
