<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Pages;

use App\Filament\Resources\Cms\GlobalTemplates\GlobalTemplateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGlobalTemplate extends ViewRecord
{
    protected static string $resource = GlobalTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
