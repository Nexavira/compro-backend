<?php

namespace App\Filament\Resources\Master\Packages;

use App\Filament\Resources\Master\Packages\Pages\EditPackage;
use App\Filament\Resources\Master\Packages\Pages\ListPackages;
use App\Filament\Resources\Master\Packages\Pages\ViewPackage;
use App\Filament\Resources\Master\Packages\Schemas\PackageForm;
use App\Filament\Resources\Master\Packages\Tables\PackagesTable;
use App\Models\Master\Package;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $modelLabel = 'Paket';
    protected static ?string $pluralModelLabel = 'Paket';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ServerStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'master-data/package';

    public static function form(Schema $schema): Schema
    {
        return PackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagesTable::configure($table);
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
            'index' => ListPackages::route('/'),
            'view' => ViewPackage::route('/{record}'),
            'edit' => EditPackage::route('/{record}/edit'),
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
