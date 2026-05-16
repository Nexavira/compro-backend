<?php

namespace App\Filament\Resources\Transaction\Payments;

use App\Filament\Resources\Transaction\Payments\Pages\CreatePayment;
use App\Filament\Resources\Transaction\Payments\Pages\EditPayment;
use App\Filament\Resources\Transaction\Payments\Pages\ListPayments;
use App\Filament\Resources\Transaction\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Transaction\Payments\Tables\PaymentsTable;
use App\Models\Transaction\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $modelLabel = 'Payment';
    protected static ?string $pluralModelLabel = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'Payment';

    protected static string|UnitEnum|null $navigationGroup = 'Transactions';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'transaction/payments';

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
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
        $query = parent::getEloquentQuery();

        if ($subId = request()->query('subscription_id')) {
            $query->where('subscription_id', $subId);
        }

        return $query;
    }
}
