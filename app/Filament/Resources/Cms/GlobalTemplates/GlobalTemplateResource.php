<?php

namespace App\Filament\Resources\Cms\GlobalTemplates;

use App\Filament\Resources\Cms\GlobalTemplates\Pages\CreateGlobalTemplate;
use App\Filament\Resources\Cms\GlobalTemplates\Pages\EditGlobalTemplate;
use App\Filament\Resources\Cms\GlobalTemplates\Pages\ListGlobalTemplates;
use App\Filament\Resources\Cms\GlobalTemplates\Schemas\GlobalTemplateForm;
use App\Filament\Resources\Cms\GlobalTemplates\Tables\GlobalTemplatesTable;
use App\Models\GlobalTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class GlobalTemplateResource extends Resource
{
    protected static ?string $model = GlobalTemplate::class;

    protected static ?string $modelLabel = 'Template Global';
    protected static ?string $pluralModelLabel = 'Template Global';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'GlobalTemplate';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|UnitEnum|null $navigationGroup = 'Sistem Konten';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'sistem-konten/template-global';

    public static function form(Schema $schema): Schema
    {
        return GlobalTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GlobalTemplatesTable::configure($table);
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
            'index' => ListGlobalTemplates::route('/'),
            'create' => CreateGlobalTemplate::route('/create'),
            'edit' => EditGlobalTemplate::route('/{record}/edit'),
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
