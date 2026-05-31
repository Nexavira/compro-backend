<?php

namespace App\Filament\Resources\Transaction\Subscriptions\Pages;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use App\Filament\Resources\Transaction\Subscriptions\SubscriptionResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;

class ManageSubscriptionPayments extends ManageRelatedRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected static string $relationship = 'payments';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $title = 'Subscription Payments';

    public static function getNavigationLabel(): string
    {
        return 'Payments';
    }

    public function table(Table $table): Table
    {
        return PaymentResource::table($table)
            ->recordTitleAttribute('invoice_number')
            ->heading('Payments for this Subscription')
            ->description('Manage payments related to this subscription.');
    }
}
