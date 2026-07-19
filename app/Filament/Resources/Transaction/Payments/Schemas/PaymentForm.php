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
                                ->label('Nomor Tagihan')
                                ->disabled()
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Invoice Number wajib diisi']),
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
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Tenant wajib diisi']),
                            TextInput::make('description')
                                ->label('Deskripsi')
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
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Status Invoice wajib diisi']),
                            TextInput::make('amount_paid')
                                ->label('Jumlah Dibayar')
                                ->numeric()
                                ->prefix('Rp')
                                ->markAsRequired($isEdit)
                                ->rules([
                                    $isEdit ? 'required' : 'nullable',
                                    fn ($get) => 'min:' . $get('amount_due'),
                                    fn ($get) => 'max:' . $get('amount_due'),
                                ])
                                ->validationMessages([
                                    'required' => 'Amount Paid is required',
                                    'min' => 'Amount Paid cannot be less than Amount Due',
                                    'max' => 'Amount Paid cannot be greater than Amount Due',
                                ]),
                            Select::make('payment_method')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'bank_transfer' => 'Bank Transfer',
                                    'cash'          => 'Cash',
                                ])
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->markAsRequired($isEdit)
                                ->rules($isEdit ? ['required'] : [])
                                ->validationMessages(['required' => 'Payment Method wajib diisi']),
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
