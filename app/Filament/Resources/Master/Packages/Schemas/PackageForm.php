<?php

namespace App\Filament\Resources\Master\Packages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        $operation = $schema->getOperation();

        return match ($operation) {
            'edit' => self::getEditSchema($schema),
            'view'   => self::getViewSchema($schema),
            default => $schema,
        };
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components(self::getFormComponents());
    }

    public static function getFormComponents(): array
    {
        return [
            Grid::make(3)->schema([
                Group::make()->schema([
                    Section::make('Informasi Dasar')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->label('Nama Paket')
                                ->maxLength(255),
                            TextInput::make('code')
                                ->required()
                                ->label('Kode Paket')
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Select::make('website_type')
                                ->label('Tipe Website')
                                ->options([
                                    'company_profile' => 'Company Profile',
                                    'ecommerce' => 'E-Commerce',
                                ])
                                ->required(),
                            Select::make('tier')
                                ->label('Tingkatan (Tier)')
                                ->options([
                                    'basic' => 'Basic',
                                    'premium' => 'Premium',
                                    'custom' => 'Custom',
                                ])
                                ->required(),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->columnSpanFull(),
                    ]),

                    Section::make('Harga & Langganan')->schema([
                        Grid::make(2)->schema([
                            Select::make('billing_cycle')
                                ->label('Siklus Tagihan')
                                ->options([
                                    'monthly' => 'Bulanan',
                                    'annually' => 'Tahunan',
                                ])
                                ->required(),
                            TextInput::make('trial_days')
                                ->label('Masa Trial (Hari)')
                                ->required()
                                ->numeric()
                                ->default(0),
                            TextInput::make('original_price')
                                ->label('Harga Coret (Asli)')
                                ->numeric()
                                ->prefix('Rp'),
                            TextInput::make('price')
                                ->label('Harga Jual')
                                ->required()
                                ->numeric()
                                ->prefix('Rp'),
                            TextInput::make('setup_fee')
                                ->label('Biaya Setup (One-time)')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp'),
                        ]),
                        Toggle::make('is_highlighted')
                            ->label('Highlight Paket (Paling Populer)'),
                    ]),
                ])->columnSpan(['sm' => 3, 'lg' => 2]),

                Group::make()->schema([
                    Section::make('Daftar Fitur')->schema([
                        Repeater::make('features')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('text')
                                    ->hiddenLabel()
                                    ->placeholder('Nama Fitur')
                                    ->required(),
                                Toggle::make('is_highlighted')
                                    ->label('Highlight (Hijau)')
                                    ->default(false),
                            ])
                            ->defaultItems(1)
                            ->reorderableWithDragAndDrop(true)
                            ->itemLabel(function (array $state) {
                                $text = $state['text'] ?? null;
                                if (!$text) return null;

                                if (!empty($state['is_highlighted'])) {
                                    return new HtmlString(
                                        '<span style="color: #10b981; font-weight: 500;">' . e($text) . '</span>'
                                    );
                                }

                                return $text;
                            })
                            ->collapsed()
                            ->addActionLabel('Tambah Fitur'),
                    ]),
                ])->columnSpan(['sm' => 3, 'lg' => 1]),
            ])->columnSpanFull(),
        ];
    }
}
