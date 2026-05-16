<?php

namespace App\Filament\Resources\Auth\Users\Schemas;

use App\Models\Auth\Role;
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
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        ->directory('profile-photos')
                        ->alignCenter()
                ])->columnSpanFull(),
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
                                ]),
                        ])->relationship('roleUser'),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
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
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label('Password Confirmation')
                            ->password()
                            ->required()
                            ->revealable()
                            ->dehydrated(false)
                            ->same('password') 
                    ])->columnSpan(1)
                ])->columnSpanFull(),     
        ];
    }
}