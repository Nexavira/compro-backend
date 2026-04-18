<?php

namespace App\Filament\Resources\Auth\Roles;

use App\Filament\Resources\Auth\Roles\Pages\ListRoles;
use App\Filament\Resources\Auth\Roles\Pages\ManagePermissionRoles;
use App\Filament\Resources\Auth\Roles\Schemas\RoleForm;
use App\Filament\Resources\Auth\Roles\Tables\RolesTable;
use UnitEnum;
use App\Models\Auth\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $modelLabel = 'Role';
    protected static ?string $pluralModelLabel = 'Roles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static ?string $recordTitleAttribute = 'Role';

    protected static string|UnitEnum|null $navigationGroup = 'Access Control';

    protected static ?string $slug = 'access-control/roles';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'permissions' => ManagePermissionRoles::route('/{record}/permissions'),
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
