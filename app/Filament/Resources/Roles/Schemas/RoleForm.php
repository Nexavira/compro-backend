<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

class RoleForm
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
            Group::make([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                Textarea::make('description')
                    ->label('Description')
            ])->columnSpanFull()
        ]);
    }

    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components([
            Group::make([

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                Textarea::make('description')
                    ->label('Description')
            ])->columnSpanFull()
        ]);
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components([
            Group::make([

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->unique(
                        table: 'auth_roles', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                Textarea::make('description')
                    ->label('Description')
            ])->columnSpanFull()
        ]);
    }
}