<?php

namespace App\Filament\Resources\Transaction\Subscriptions;

use App\Filament\Resources\Transaction\Subscriptions\Pages\CreateSubscription;
use App\Filament\Resources\Transaction\Subscriptions\Pages\EditSubscription;
use App\Filament\Resources\Transaction\Subscriptions\Pages\ListSubscriptions;
use App\Filament\Resources\Transaction\Subscriptions\Pages\ViewSubscription;
use App\Filament\Resources\Transaction\Subscriptions\Schemas\SubscriptionForm;
use App\Filament\Resources\Transaction\Subscriptions\Schemas\SubscriptionInfolist;
use App\Filament\Resources\Transaction\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Transaction\Subscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $modelLabel = 'Subscription';
    protected static ?string $pluralModelLabel = 'Subscriptions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $recordTitleAttribute = 'Subcription';

    protected static string|UnitEnum|null $navigationGroup = 'Transactions';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'transaction/subscriptions';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SubscriptionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
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
            'index' => ListSubscriptions::route('/'),
            'create' => CreateSubscription::route('/create'),
            'view' => ViewSubscription::route('/{record}'),
            'edit' => EditSubscription::route('/{record}/edit'),
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
