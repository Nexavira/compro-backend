<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
            ])
            ->filters([
                // TrashedFilter::make(),
                // SelectFilter::make('is_active')
                //     ->label('Active Status')
                //     ->options([
                //         1 => 'Active',
                //         0 => 'Inactive',
                // ]),
            ])
            ->checkIfRecordIsSelectableUsing(function ($record): bool {
                return $record->code !== 'master_admin';
            })
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(fn ($record) => $record->update(['is_active' => false])),
                    Action::make('manage_permissions')
                        ->label('Permissions')
                        ->icon('heroicon-m-shield-check')
                        ->color('warning')
                        ->url(fn ($record): string => RoleResource::getUrl('permissions', ['record' => $record]))
                        ->visible(fn ($record) => can_access('admin_auth_permission_view') && $record->code !== 'master_admin'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->code !== 'master_admin') {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }
}
