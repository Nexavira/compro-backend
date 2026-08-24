<?php

namespace App\Filament\Resources\Master\Packages\Pages;

use App\Filament\Resources\Master\Packages\PackageResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;

class EditPackage extends EditRecord
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
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            $this->getRecordTitle(),
        ];
    }

    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::Left;
    }
}
