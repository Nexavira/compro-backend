<?php

namespace App\Filament\Resources\Tenant\Tenants\Pages;

use App\Filament\Resources\Tenant\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
