<?php

namespace App\Filament\Resources\Auth\Users\Schemas;

use App\Models\Auth\Role;
use App\Models\Tenant\Tenant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Illuminate\Support\Facades\Hash;

class UserForm
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
        return $schema->components(self::getFormComponents());
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getFormComponents(): array
    {
        return [
            Group::make([
                FileUpload::make('photo_upload')
                    ->label('Foto Profil')
                    ->image()
                    ->avatar()
                    ->directory('profile-photos')
                    ->alignCenter()
                    ->formatStateUsing(fn($record) => $record?->photo?->file_path)
            ])->relationship('userDetail')
                ->columnSpanFull(),
            Grid::make(2)
                ->schema([
                    Group::make([
                        Group::make([
                            TextInput::make('full_name')
                                ->label('Full Name')
                                ->required(),
                            TextInput::make('phone_number')
                                ->label('Phone Number')
                                ->tel(),
                            Select::make('tenant_id')
                                ->label('Tenant')
                                ->options(Tenant::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ]),
                        ])->relationship('userDetail'),
                        Group::make([
                            Select::make('role_id')
                                ->label('Role')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->formatStateUsing(fn ($record) => $record?->role_id),
                        ])->relationship('roleUser'),
                    ])->columnSpan(1),
                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(
                                table: 'auth_users',
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule) {
                                    return $rule->whereNull('deleted_at');
                                }
                            ),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->hiddenOn(['edit', 'view'])
                            ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label('Password Confirmation')
                            ->password()
                            ->required()
                            ->revealable()
                            ->hiddenOn(['edit', 'view'])
                            ->dehydrated(false)
                            ->same('password'),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false),
                    ])->columnSpan(1)
                ])->columnSpanFull(),
        ];
    }
}
