<?php

namespace App\Filament\Resources\Tenant\Tenants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TenantForm
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
            Grid::make(3)
                ->schema([
                    Group::make()->schema([
                        Section::make('Client Information')
                            ->description('Client name will be used to generate the URL prefix (slug) automatically.')
                            ->columns(2) 
                            ->schema([
                                TextInput::make('name')
                                    ->label('Client Name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                                    
                                TextInput::make('slug')
                                    ->label('Slug / URL Prefix')
                                    ->required()
                                    ->unique(table: 'tnt_tenants', ignoreRecord: true),
                                    
                                Select::make('tenant_category_id')
                                    ->label('Category')
                                    ->relationship('tenantCategory', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Technical Configuration')
                            ->description('Set UI theme and custom domain for this tenant.')
                            ->columns(2) 
                            ->schema([
                                Select::make('theme_code')
                                    ->label('UI Theme')
                                    ->options([
                                        'light' => 'Light',
                                        'dark' => 'Dark',
                                    ])
                                    ->default('light')
                                    ->required(),
                                    
                                TextInput::make('custom_domain')
                                    ->label('Custom Domain')
                                    ->placeholder('client.com')
                                    ->prefix('https://'),
                            ]),
                    ])->columnSpan(['lg' => 2]),
                    Group::make()->schema([
                        Section::make('Status')
                            ->schema([
                                Toggle::make('is_suspended')
                                    ->label('Suspend Tenant?')
                                    ->helperText('If active, the tenant will not be able to access the system.')
                                    ->onColor('danger')
                                    ->offColor('success'),
                            ]),

                        Section::make('Branding')
                            ->description('Upload logo and favicon.')
                            ->schema([
                                FileUpload::make('logo_id')
                                    ->label('Company Logo')
                                    ->image()
                                    ->directory('tenants/logos'),
                                    
                                FileUpload::make('favicon_id')
                                    ->label('Favicon')
                                    ->image()
                                    ->directory('tenants/favicons'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
                    
                ])
                ->columnSpanFull(),
        ];
    }
}