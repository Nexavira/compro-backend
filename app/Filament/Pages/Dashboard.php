<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Beranda';
    protected static ?string $title = 'Beranda';

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Beranda';
    }
}
