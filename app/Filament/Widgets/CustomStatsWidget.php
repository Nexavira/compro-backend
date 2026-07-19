<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Carbon\Carbon;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Subscription;
use App\Models\Transaction\Payment;

class CustomStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.custom-stats-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getViewData(): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalKlien = Tenant::count();
        $klienAktif = Tenant::where('is_suspended', 0)->where('is_active', 1)->count();

        $totalLangganan = Subscription::where('status', 'active')->count();

        $totalPendapatan = Payment::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount_paid');

        return [
            'totalKlien' => $totalKlien,
            'klienAktif' => $klienAktif,
            'totalLangganan' => $totalLangganan,
            'totalPendapatan' => $totalPendapatan,
        ];
    }
}
