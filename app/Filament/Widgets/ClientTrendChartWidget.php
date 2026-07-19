<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Tenant\Tenant;

class ClientTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Pendaftaran Klien';
    protected ?string $description = 'Pertumbuhan tenant baru sepanjang tahun 2026';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $year = $this->filter ?: date('Y');
        $this->description = 'Pertumbuhan tenant baru sepanjang tahun ' . $year;

        $tenants = Tenant::whereBetween('created_at', [
            Carbon::createFromDate((int)$year, 1, 1)->startOfDay()->timestamp,
            Carbon::createFromDate((int)$year, 12, 31)->endOfDay()->timestamp
        ])->get();

        $data = array_fill(1, 12, 0);
        foreach ($tenants as $tenant) {
            $month = Carbon::createFromTimestamp($tenant->created_at)->month;
            $data[$month]++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Klien Baru',
                    'data' => array_values($data),
                    'borderColor' => '#059669', 
                    'backgroundColor' => 'rgba(5, 150, 105, 0.1)', 
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
