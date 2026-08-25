<?php

namespace App\Filament\Resources\Transaction\Payments\Tables;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use App\Models\Auth\DetailUser;
use Filament\Actions\Action;
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
                    ->label('Nomor Tagihan')
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
                    ->label('Jumlah Dibayar')
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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->label('Payment Verification')
                        ->button()
                        ->hidden(fn($record) => $record->status === 'paid')
                        ->url(fn($record) => PaymentResource::getUrl('edit', [
                            'record' => $record,
                        ])),
                    Action::make('whatsapp')
                        ->label('Share via WA')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->color('success')
                        ->hidden(fn($record) => $record->status === 'paid')
                        ->url(function ($record) {
                            $adminDetail = DetailUser::where('tenant_id', $record->tenant_id)->first();
                            $phone = $adminDetail?->phone_number ?? '';

                            // Format phone number to international format (62...)
                            if (str_starts_with($phone, '0')) {
                                $phone = '62' . substr($phone, 1);
                            }

                            $price = number_format($record->amount_due, 0, ',', '.');
                            $message = "Hello, this is your system subscription invoice.\n\nInvoice Number: {$record->invoice_number}\nTotal Due: Rp {$price}\n\nPlease make a payment so the system can be accessed. Thank you.";

                            return "https://wa.me/{$phone}?text=" . urlencode($message);
                        })
                        ->openUrlInNewTab()
                        ->button(),
                    Action::make('whatsapp_thanks')
                        ->label('Send Payment Proof via WA')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'paid')
                        ->url(function ($record) {
                            $adminDetail = DetailUser::where('tenant_id', $record->tenant_id)->first();
                            $phone = $adminDetail?->phone_number ?? '';

                            if (str_starts_with($phone, '0')) {
                                $phone = '62' . substr($phone, 1);
                            }

                            $invoiceUrl = url('/');
                            $message = "Hello, thank you for making the payment.\n\nInvoice Number: {$record->invoice_number}\nStatus: PAID\n\nThank you for using our services.";

                            return "https://wa.me/{$phone}?text=" . urlencode($message);
                        })
                        ->openUrlInNewTab()
                        ->button(),
                ]),
            ]);
    }
}
