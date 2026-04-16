<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Pages;

use App\Filament\Resources\Cms\GlobalTemplates\GlobalTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGlobalTemplates extends ListRecords
{
    protected static string $resource = GlobalTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
