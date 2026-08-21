<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-banner-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function requestConcierge()
    {
        $user = auth()->user();
        if ($user && $user->userDetail && $user->userDetail->tenant) {
            $tenant = $user->userDetail->tenant;
            if ($tenant->concierge_status !== 'completed' && $tenant->concierge_status !== 'requested') {
                $tenant->concierge_status = 'requested';
                $tenant->save();

                \Filament\Notifications\Notification::make()
                    ->title('Permintaan Terkirim')
                    ->body('Tim kami akan segera menghubungi Anda untuk proses setup gratis.')
                    ->success()
                    ->send();
            }
        }
    }
}
