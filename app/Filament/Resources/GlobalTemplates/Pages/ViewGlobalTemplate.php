<?php

namespace App\Filament\Resources\GlobalTemplates\Pages;

use App\Filament\Resources\GlobalTemplates\GlobalTemplateResource;
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
