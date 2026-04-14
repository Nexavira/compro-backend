<?php

namespace App\Filament\Resources\Users\Tables;

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

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_id')
                    ->label('Profile Picture')
                    ->circular()
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('userDetail.full_name')
                    ->label('Full Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('userDetail.phone_number')
                    ->label('Phone Number')
                    ->searchable(),
                TextColumn::make('roleUser.role.name')
                    ->label('Role')
                    ->badge()
                    ->color('info')
                    ->default('No Role Assigned'),
            ])
            ->filters([
                TrashedFilter::make(),
                // SelectFilter::make('role')
                //     ->label('Role')
                //     ->relationship('roleUser', 'role_id')
                //     ->options(
                //         Role::all()->pluck('name', 'id')->toArray()
                //     )
                //     ->native(false)
                //     ->searchable() 
                //     ->preload()
            ])
            ->checkIfRecordIsSelectableUsing(function ($record): bool {
                return $record->roleUser?->role?->code !== 'master_admin';
            })
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(fn ($record) => $record->update(['is_active' => false])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $isMaster = ($record->roleUser->role->code === 'master_admin') || 
                                        (method_exists($record, 'role') && $record->roleUser->role?->code === 'master_admin');

                                if (! $isMaster) {
                                    $record->delete();
                                }
                            });
                        }),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
