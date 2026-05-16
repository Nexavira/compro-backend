<?php

namespace App\Filament\Resources\System\ApiKeys\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ApiKeysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('token')
                    ->label('API Key')
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('API Key copied to clipboard')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Never used')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(fn ($record) => $record->update(['is_active' => 0])),
                ]),
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
