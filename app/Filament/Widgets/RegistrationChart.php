<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RegistrationChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendaftaran Klien';
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full'; 
    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Klien Baru',
                    'data' => [1, 0, 2, 4, 1, 4, 3, 5, 2, 6, 4, 7],
                    'borderColor' => '#1E90FF',
                    'backgroundColor' => 'rgba(30, 144, 255, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
