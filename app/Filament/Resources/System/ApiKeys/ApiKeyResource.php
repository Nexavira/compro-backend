<?php

namespace App\Filament\Resources\System\ApiKeys;

use App\Filament\Resources\System\ApiKeys\Pages\CreateApiKey;
use App\Filament\Resources\System\ApiKeys\Pages\EditApiKey;
use App\Filament\Resources\System\ApiKeys\Pages\ListApiKeys;
use App\Filament\Resources\System\ApiKeys\Schemas\ApiKeyForm;
use App\Filament\Resources\System\ApiKeys\Tables\ApiKeysTable;
use App\Models\ApiKey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $modelLabel = 'API Key';
    protected static ?string $pluralModelLabel = 'API Key';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Key;

    protected static ?string $recordTitleAttribute = 'ApiKey';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'sistem/api-key';

    public static function form(Schema $schema): Schema
    {
        return ApiKeyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiKeysTable::configure($table);
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
            'index' => ListApiKeys::route('/'),
            'create' => CreateApiKey::route('/create'),
            'edit' => EditApiKey::route('/{record}/edit'),
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
