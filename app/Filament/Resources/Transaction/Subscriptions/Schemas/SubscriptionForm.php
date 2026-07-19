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
                ->native(false)
                ->searchable()
                ->preload()
                ->extraAttributes([
                    'style' => 'cursor: pointer !important;',
                ])
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Tenant wajib diisi']),

            Select::make('package_id')
                ->label('Paket')
                ->relationship('package', 'name')
                ->native(false)
                ->searchable()
                ->preload()
                ->extraAttributes([
                    'style' => 'cursor: pointer !important;',
                ])
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
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Package wajib diisi']),

            Hidden::make('package_name')
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Package Name wajib diisi']),
            Select::make('status')
                ->label('Status')
                ->options([
                    'active'    => 'Active',
                    'past_due'  => 'Past Due',
                    'canceled'  => 'Canceled',
                ])
                ->native(false)
                ->searchable()
                ->preload()
                ->extraAttributes([
                    'style' => 'cursor: pointer !important;',
                ])
                ->default('active')
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Status wajib diisi']),
            Select::make('billing_cycle')
                ->label('Billing Cycle')
                ->options([
                    'monthly'  => 'Monthly',
                    'annually' => 'Annually',
                    'custom'   => 'Custom',
                ])
                ->native(false)
                ->searchable()
                ->preload()
                ->extraAttributes([
                    'style' => 'cursor: pointer !important;',
                ])
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Billing Cycle wajib diisi']),
            DatePicker::make('next_billing_date')
                ->label('Next Billing Date')
                ->default(now()->addMonth())
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Next Billing Date wajib diisi']),
            TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->prefix('Rp')
                ->markAsRequired()
                ->rules(['required'])
                ->validationMessages(['required' => 'Amount wajib diisi']),
        ];
    }
}
