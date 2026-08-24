<?php

namespace App\Filament\Resources\Master\Packages\Pages;

use App\Filament\Resources\Master\Packages\PackageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPackage extends ViewRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            filament()->getHomeUrl() => 'Beranda',
            static::getResource()::getNavigationGroup(),
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            $this->getRecordTitle(),
        ];
    }
}
