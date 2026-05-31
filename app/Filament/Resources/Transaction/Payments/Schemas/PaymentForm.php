<?php

namespace App\Filament\Resources\Transaction\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
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
        return $schema->components(self::getFormComponents(isEdit: false));
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents(isEdit: true));
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents(isEdit: false));
    }

    protected static function getFormComponents(bool $isEdit = false): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Section::make('Billing Information')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('invoice_number')
                                ->label('Invoice Number')
                                ->disabled()
                                ->required(),
                            Select::make('tenant_id')
                                ->label('Tenant')
                                ->relationship('tenant', 'name')
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->disabled()
                                ->required(),
                            TextInput::make('description')
                                ->label('Description')
                                ->disabled(),
                            Grid::make(2)->schema([
                                TextInput::make('amount_due')
                                    ->label('Amount Due')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                                DatePicker::make('due_date')
                                    ->label('Due Date')
                                    ->disabled(),
                            ]),
                        ]),
                    Section::make('Payment Verification')
                        ->columnSpan(1)
                        ->schema([
                            Select::make('status')
                                ->label('Status Invoice')
                                ->options([
                                    'unpaid'               => 'Unpaid',
                                    'pending_verification' => 'Pending Verification',
                                    'paid'                 => 'Paid',
                                    'failed'               => 'Failed',
                                ])
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->required(),
                            TextInput::make('amount_paid')
                                ->label('Amount Paid')
                                ->numeric()
                                ->prefix('Rp')
                                ->required($isEdit),
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'bank_transfer' => 'Bank Transfer',
                                    'midtrans'      => 'Otomatis (Gateway)',
                                ])
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->required($isEdit),
                            FileUpload::make('proof_of_payment_upload')
                                ->label('Proof of Payment')
                                ->image()
                                ->disk('public')
                                ->directory('payments/proofs')
                                ->visibility('public')
                                ->formatStateUsing(fn ($record) => $record?->proofOfPayment?->file_path),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
