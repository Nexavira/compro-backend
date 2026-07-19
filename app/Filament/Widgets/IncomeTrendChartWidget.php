<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Transaction\Payment;

class IncomeTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan Bulanan';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $year = $this->filter ?: date('Y');
        $this->description = 'Total pendapatan (berdasarkan payment date) sepanjang tahun ' . $year;

        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $payments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->get();

        $data = array_fill(1, 12, 0);
        foreach ($payments as $payment) {
            $month = Carbon::parse($payment->payment_date)->month;
            $data[$month] += $payment->amount_paid;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => array_values($data),
                    'borderColor' => '#dc2626', 
                    'backgroundColor' => 'rgba(220, 38, 38, 0.1)', 
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        $currentYear = date('Y');
        return [
            $currentYear => 'Tahun ' . $currentYear,
            ($currentYear - 1) => 'Tahun ' . ($currentYear - 1),
        ];
    }
}
