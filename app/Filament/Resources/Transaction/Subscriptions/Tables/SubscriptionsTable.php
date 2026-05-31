<?php

namespace App\Filament\Resources\Transaction\Subscriptions\Tables;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subscription_number')
                    ->label('Subscription Number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('tenant.name')
                    ->label('Tenant'),
                TextColumn::make('package.name')
                    ->label('Package'),
                TextColumn::make('billing_cycle')
                    ->label('Billing Cycle')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('next_billing_date')
                    ->label('Next Billing Date')
                    ->date('d F Y', 'Asia/Jakarta'),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR', locale:'id_ID')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'inactive' => 'gray',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('viewInvoices')
                        ->label('Lihat Invoice')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn ($record) => PaymentResource::getUrl('index', ['subscription_uuid' => $record->uuid])),
                    ViewAction::make(),
                    DeleteAction::make()
                        ->before(fn ($record) => $record->update(['is_active' => false])),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
