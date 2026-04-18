<?php

namespace App\Filament\Resources\Tenant\Tenants;

use App\Filament\Resources\Tenant\Tenants\Pages\CreateTenant;
use App\Filament\Resources\Tenant\Tenants\Pages\EditTenant;
use App\Filament\Resources\Tenant\Tenants\Pages\ListTenants;
use App\Filament\Resources\Tenant\Tenants\Pages\ViewTenant;
use App\Filament\Resources\Tenant\Tenants\Schemas\TenantForm;
use App\Filament\Resources\Tenant\Tenants\Schemas\TenantInfolist;
use App\Filament\Resources\Tenant\Tenants\Tables\TenantsTable;
use App\Models\Tenant\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $modelLabel = 'Tenant';
    protected static ?string $pluralModelLabel = 'Lists';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static ?string $recordTitleAttribute = 'Tenant';

    protected static string|UnitEnum|null $navigationGroup = 'Tenants';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'tenants/tenants';

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
