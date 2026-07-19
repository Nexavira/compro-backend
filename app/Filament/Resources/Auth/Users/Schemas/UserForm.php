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
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages([
                                    'required' => 'Full Name is required'
                                ]),
                            TextInput::make('phone_number')
                                ->label('Phone Number')
                                ->markAsRequired()
                                ->rules([
                                    'required',
                                    'regex:/^[0-9]+$/',
                                    'starts_with:62',
                                    'min_digits:10',
                                    'max_digits:14',
                                ])
                                ->validationMessages([
                                    'required' => 'Phone Number is required',
                                    'regex' => 'Phone Number must be numeric',
                                    'starts_with' => 'Phone Number must start with 62',
                                    'min_digits' => 'Phone Number must be at least 10 digits',
                                    'max_digits' => 'Phone Number cannot exceed 14 digits',
                                ]),
                            Select::make('tenant_id')
                                ->label('Tenant')
                                ->options(Tenant::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->markAsRequired()
                                ->rules([
                                    'required'
                                ])
                                ->validationMessages([
                                    'required' => 'Tenant must be selected'
                                ])
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ]),
                        ])->relationship('userDetail'),
                        Group::make([
                            Select::make('role_id')
                                ->label('Peran')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->markAsRequired()
                                ->rules([
                                    'required'
                                ])
                                ->validationMessages([
                                    'required' => 'Role must be selected'
                                ])
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->formatStateUsing(fn($record) => $record?->role_id),
                        ])->relationship('roleUser'),
                    ])->columnSpan(1),
                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->markAsRequired()
                            ->rules([
                                'required',
                                'email'
                            ])
                            ->validationMessages([
                                'required' => 'Email is required',
                                'email' => 'Format Email is invalid'
                            ])
                            ->unique(
                                table: 'auth_users',
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule) {
                                    return $rule->whereNull('deleted_at');
                                }
                            ),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->markAsRequired()
                            ->rules([
                                'required',
                                'min:8'
                            ])
                            ->validationMessages([
                                'required' => 'Password is required',
                                'min' => 'Password must be at least 8 characters'
                            ])
                            ->revealable()
                            ->hiddenOn(['edit', 'view'])
                            ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label('Password Confirmation')
                            ->password()
                            ->markAsRequired()
                            ->rules([
                                'required'
                            ])
                            ->validationMessages([
                                'required' => 'Password Confirmation is required',
                                'same' => 'Password Confirmation does not match'
                            ])
                            ->revealable()
                            ->hiddenOn(['edit', 'view'])
                            ->dehydrated(false)
                            ->same('password'),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->inline(false),
                    ])->columnSpan(1)
                ])->columnSpanFull(),
        ];
    }
}
