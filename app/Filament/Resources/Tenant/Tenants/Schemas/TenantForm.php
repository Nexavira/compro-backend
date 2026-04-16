<?php

namespace App\Filament\Resources\Tenant\Tenants\Schemas;

use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
