<?php

namespace App\Filament\Resources\Master\Packages\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                TextColumn::make('website_type')
                    ->label('Tipe Website')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn(string $state) => ucwords(str_replace(['_', '-'], ' ', $state)))
                    ->color(fn(string $state): string|array => match ($state) {
                        'company_profile' => Color::hex('#4f46e5'),
                        'commerce' => Color::hex('#10b981'),
                        default => 'gray',
                    }),
                TextColumn::make('tier')
                    ->label('Tier')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->color(fn(string $state): string|array => match ($state) {
                        'basic' => Color::hex('#06b6d4'),
                        'premium' => Color::hex('#f59e0b'),
                        'custom' => Color::hex('#8b5cf6'),
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('billing_cycle')
                    ->label('Siklus')
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->searchable(),
                IconColumn::make('is_highlighted')
                    ->label('Populer')
                    ->boolean(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => '<div style="display: flex; justify-content: space-between; min-width: 10px;"><span>Rp</span><span>' . number_format($state, 0, ',', '.') . '</span></div>')
                    ->html()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('website_type')
                    ->label('Tipe Website')
                    ->options([
                        'company_profile' => 'Company Profile',
                        'commerce' => 'E-Commerce',
                    ]),
                SelectFilter::make('tier')
                    ->label('Tier')
                    ->options([
                        'basic' => 'Basic',
                        'premium' => 'Premium',
                        'custom' => 'Custom',
                    ]),
                SelectFilter::make('billing_cycle')
                    ->label('Siklus')
                    ->options([
                        'monthly' => 'Monthly',
                        'annually' => 'Annually',
                    ]),
            ])
            // ->recordAction(ViewAction::class)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
