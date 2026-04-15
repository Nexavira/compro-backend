<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ActivityLogWidget extends Widget
{
    protected string $view = 'filament.widgets.activity-log-widget';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
}
