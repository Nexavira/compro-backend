<?php

namespace App\Filament\Resources\Transaction\Payments\Tables;

use App\Models\Auth\UserDetail;
use Filament\Actions\Action;
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
                    ->sortable()
                    ->color(function ($record) {
                        if ($record->status === 'paid' || !$record->due_date) {
                            return null;
                        }
                        $dueDate = \Carbon\Carbon::parse($record->due_date);
                        $days = now()->startOfDay()->diffInDays($dueDate->startOfDay(), false);
                        return $days <= 5 ? 'danger' : null;
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger'  => 'unpaid',
                        'warning' => 'pending_verification',
                        'success' => 'paid',
                        'secondary' => 'failed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'unpaid'               => 'Unpaid',
                        'pending_verification' => 'Pending Verification',
                        'paid'                 => 'Paid',
                        'failed'               => 'Failed',
                        default                => $state,
                    }),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->label('Payment Verification')
                    ->button()
                    ->hidden(fn($record) => $record->status === 'paid'),
                Action::make('whatsapp')
                    ->label('Bagikan WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function ($record) {
                        $adminDetail = UserDetail::where('tenant_id', $record->tenant_id)->first();
                        $phone = $adminDetail?->phone_number ?? '';

                        // Format phone number to international format (62...)
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }

                        $price = number_format($record->amount_due, 0, ',', '.');
                        $message = "Halo, ini adalah tagihan langganan sistem Anda.\n\nNomor Invoice: {$record->invoice_number}\nTotal Tagihan: Rp {$price}\n\nSilakan lakukan pembayaran agar sistem dapat diakses. Terima kasih.";

                        return "https://wa.me/{$phone}?text=" . urlencode($message);
                    })
                    ->openUrlInNewTab()
                    ->button(),
            ]);
    }
}
