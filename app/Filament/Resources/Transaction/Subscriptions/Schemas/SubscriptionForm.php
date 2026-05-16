<?php

namespace App\Filament\Resources\Transaction\Subscriptions\Schemas;

use App\Models\Master\Package;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        $operation = $schema->getOperation();

        return match ($operation) {
            'create' => self::getCreateSchema($schema),
            'edit'   => self::getEditSchema($schema),
            'view'   => self::getViewSchema($schema),
            default  => $schema,
        };
    }

    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getFormComponents(): array
    {
        return [
            TextInput::make('subscription_number')
                ->label('Nomor Langganan (Contract No.)')
                ->disabled()
                ->hiddenOn('create')
                ->columnSpanFull(),
            Select::make('tenant_id')
                ->label('Tenant')
                ->relationship('tenant', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('package_id')
                ->label('Package')
                ->relationship('package', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    if (!blank($state)) {
                        $package = Package::find($state);
                        if ($package) {
                            $set('package_name', $package->name);
                            $set('amount', $package->price);
                            $set('billing_cycle', $package->billing_cycle);
                        }
                    } else {
                        $set('package_name', null);
                        $set('amount', null);
                        $set('billing_cycle', null);
                    }
                })
                ->required(),

            Hidden::make('package_name')
                ->required(),
            Select::make('status')
                ->label('Status')
                ->options([
                    'active'    => 'Active',
                    'past_due'  => 'Past Due',
                    'canceled'  => 'Canceled',
                ])
                ->default('active')
                ->required(),
            Select::make('billing_cycle')
                ->label('Billing Cycle')
                ->options([
                    'monthly'  => 'Monthly',
                    'annually' => 'Annually',
                    'custom'   => 'Custom',
                ])
                ->required(),
            DatePicker::make('next_billing_date')
                ->label('Next Billing Date')
                ->default(now()->addMonth())
                ->required(),
            TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->prefix('Rp')
                ->required(),
        ];
    }
}
