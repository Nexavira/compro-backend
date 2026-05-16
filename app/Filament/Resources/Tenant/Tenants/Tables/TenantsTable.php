<?php

namespace App\Filament\Resources\Tenant\Tenants\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo.file_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-logo.png')),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->checkIfRecordIsSelectableUsing(function ($record): bool {
                return $record->name !== 'Nexavira';
            })
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(fn ($record) => $record->update(['is_active' => false])),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->name !== 'Nexavira') {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }
}
