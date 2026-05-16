<?php

namespace App\Filament\Resources\Cms\Posts\Pages;

use App\Filament\Resources\Cms\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
