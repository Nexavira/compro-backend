<?php

namespace App\Filament\Resources\GlobalTemplates;

use App\Filament\Resources\GlobalTemplates\Pages\CreateGlobalTemplate;
use App\Filament\Resources\GlobalTemplates\Pages\EditGlobalTemplate;
use App\Filament\Resources\GlobalTemplates\Pages\ListGlobalTemplates;
use App\Filament\Resources\GlobalTemplates\Pages\ViewGlobalTemplate;
use App\Filament\Resources\GlobalTemplates\Schemas\GlobalTemplateForm;
use App\Filament\Resources\GlobalTemplates\Schemas\GlobalTemplateInfolist;
use App\Filament\Resources\GlobalTemplates\Tables\GlobalTemplatesTable;
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

    protected static ?string $modelLabel = 'Global Template';
    protected static ?string $pluralModelLabel = 'Global Templates';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'GlobalTemplate';

    protected static string|UnitEnum|null $navigationGroup = 'Global Template Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'global-template-management/global-templates';

    public static function form(Schema $schema): Schema
    {
        return GlobalTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GlobalTemplateInfolist::configure($schema);
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
            'view' => ViewGlobalTemplate::route('/{record}'),
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
}
