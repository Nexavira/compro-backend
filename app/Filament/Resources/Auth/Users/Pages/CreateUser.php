<?php

namespace App\Filament\Resources\Auth\Users\Pages;

use App\Filament\Resources\Auth\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
