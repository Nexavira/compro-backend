<?php

namespace App\Filament\Resources\Master\Packages\Pages;

use App\Filament\Resources\Master\Packages\PackageResource;
use Filament\Resources\Pages\ListRecords;

class ListPackages extends ListRecords
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getBreadcrumbs(): array
    {
        return [
            filament()->getHomeUrl() => 'Beranda',
            static::getResource()::getNavigationGroup(),
            static::getResource()::getBreadcrumb(),
        ];
    }
}
