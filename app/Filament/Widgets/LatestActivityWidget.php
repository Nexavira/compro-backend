<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\System\ActivityLog;

class LatestActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.latest-activity-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected function getViewData(): array
    {
        $activities = ActivityLog::with('causer')->orderBy('created_at', 'desc')->take(5)->get();

        return [
            'activities' => $activities,
        ];
    }
}
