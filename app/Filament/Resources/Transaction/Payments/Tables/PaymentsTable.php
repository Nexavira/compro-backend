<?php

namespace App\Filament\Resources\Transaction\Payments\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable(),
                TextColumn::make('amount_due')
                    ->label('Amount Due')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('amount_paid')
                    ->label('Amount Paid')
                    ->money('IDR', locale: 'id')
                    ->placeholder('Rp 0'),
                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger'  => 'unpaid',
                        'warning' => 'pending_verification',
                        'success' => 'paid',
                        'secondary' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid'               => 'Unpaid',
                        'pending_verification' => 'Pending Verification',
                        'paid'                 => 'Paid',
                        'failed'               => 'Failed',
                        default                => $state,
                    }),
            ])
            ->filters([])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->label('Payment Verification'), 
                ]),
            ]);
    }
}
