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

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components([
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
                            TextInput::make('role_name')
                                ->label('Role')
                                ->afterStateHydrated(function ($component, $record) {
                                    $component->state($record->roleUser?->role?->name ?? '-');
                                })
                                ->disabled(),
                        ]),
                        // Toggle::make('is_active')
                        //     ->label('Status Aktif')
                        //     ->afterStateHydrated(function ($component, $record) {
                        //         $component->state((bool) ($record->is_active ?? false));
                        //     })
                        //     ->disabled(),
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
                        // TextInput::make('password')
                        //     ->label('Password')
                        //     ->password()
                        //     ->required()
                        //     ->revealable()
                        //     ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        // TextInput::make('password_confirmation')
                        //     ->label('Password Confirmation')
                        //     ->password()
                        //     ->required()
                        //     ->revealable()
                        //     ->dehydrated(false)
                        //     ->same('password')
                    ])->columnSpan(1)
                ])->columnSpanFull(),     
        ]);
    }

    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        ->directory('profile-photos')
                        ->alignCenter(),
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
                                ->options(
                                    Role::all()->pluck('name', 'id')
                                )
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
                            // ->helperText(new HtmlString('<span style="color: #dc2626; font-size: 0.875rem;">Masukkan ulang password untuk konfirmasi</span>'))
                    ])->columnSpan(1)
                ])->columnSpanFull(),    
        ]);
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        ->directory('profile-photos')
                        ->alignCenter(),
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

                        Select::make('role_id')
                            ->label('Role')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateHydrated(function (Select $component, $record) {
                                $component->state($record->roleUser?->role_id);
                            })
                            ->dehydrated(false) 
                            ->saveRelationshipsUsing(function ($state, $record) {
                                $record->roleUser()->updateOrCreate(
                                    ['user_id' => $record->id],
                                    ['role_id' => $state] 
                                );
                            })
                        // Toggle::make('is_active')
                        //     ->label('Status Aktif')
                        //     ->afterStateHydrated(fn ($component, $record) => $component->state((bool) ($record->is_active ?? false))),
                    ])->columnSpan(1),

                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(table: 'auth_users', ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Password Baru (Kosongkan jika tidak mengubah password)')
                            ->password()
                            ->revealable()
                            ->live(debounce: 500) 
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state)),

                        TextInput::make('password_confirmation')
                            ->label('Password Confirmation Baru')
                            ->password()
                            ->revealable()
                            ->required(fn ($get) => filled($get('password')))
                            ->visible(fn ($get) => filled($get('password')))
                            ->same('password')
                            ->dehydrated(false),
                    ])->columnSpan(1),
                ])->columnSpanFull(),    
        ]);
    }
}