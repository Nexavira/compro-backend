<?php

namespace App\Filament\Resources\Cms\Pages\Pages;

use App\Filament\Resources\Cms\Pages\PageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
