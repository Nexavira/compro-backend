<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Pages;

use App\Filament\Resources\Cms\GlobalTemplates\GlobalTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGlobalTemplate extends CreateRecord
{
    protected static string $resource = GlobalTemplateResource::class;

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
