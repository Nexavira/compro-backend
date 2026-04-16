<?php

namespace App\Filament\Resources\System\ApiKeys\Pages;

use App\Filament\Resources\System\ApiKeys\ApiKeyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;
}
