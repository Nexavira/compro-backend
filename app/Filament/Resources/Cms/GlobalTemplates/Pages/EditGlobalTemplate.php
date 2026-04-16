<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Pages;

use App\Filament\Resources\Cms\GlobalTemplates\GlobalTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGlobalTemplate extends EditRecord
{
    protected static string $resource = GlobalTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
