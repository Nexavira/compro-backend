<?php

namespace App\Filament\Resources\Cms\Pages\Pages;

use App\Filament\Resources\Cms\Pages\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin') => __('Beranda'),
            static::getResource()::getNavigationGroup(),
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            'Tambah',
        ];
    }
}
