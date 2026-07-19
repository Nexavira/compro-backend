<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class GlobalTemplateForm
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
                    Grid::make(1)
                        ->columnSpan(2)
                        ->schema([
                            Section::make('Template Identity')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Template Title')
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'Template Title is required'
                                        ])
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                                            if ($operation === 'create' && $state) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),

                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'URL Slug is required'
                                        ])
                                        ->unique(ignoreRecord: true),

                                    Textarea::make('description')
                                        ->label('Template Description')
                                        ->columnSpanFull()
                                        ->rows(2),
                                ]),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Template Settings')
                                ->schema([
                                    Select::make('tenant_category_id')
                                        ->label('Category')
                                        ->relationship('tenantCategory', 'name')
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'Template Category is required'
                                        ]),

                                    Select::make('is_active')
                                        ->label('Template Status')
                                        ->options([
                                            1 => 'Active',
                                            0 => 'Inactive',
                                        ])
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->default(1)
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'Template Status is required'
                                        ]),
                                ]),
                        ]),

                    Section::make('Template Brand Settings (Fonts, Colors, Social Links)')
                        ->description('Konfigurasi brand default untuk template ini.')
                        ->statePath('brand_settings')
                        ->columnSpanFull()
                        ->schema([
                            Fieldset::make('Brand Identity')
                                ->statePath('brand')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')->label('Brand Name')->required(),
                                    ColorPicker::make('primaryColor')->label('Primary Color')->required(),
                                    ColorPicker::make('accentColor')->label('Accent Color')->required(),
                                    Fieldset::make('Font Pairing')
                                        ->statePath('fontPairing')
                                        ->columns(2)
                                        ->schema([
                                            TextInput::make('heading')->label('Heading Font'),
                                            TextInput::make('body')->label('Body Font'),
                                        ]),
                                ]),
                            Fieldset::make('Social Links')
                                ->statePath('social')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('instagram')->label('Instagram URL'),
                                    TextInput::make('twitter')->label('Twitter / X URL'),
                                ]),
                        ])->collapsible(),

                    Section::make('Template Pages')
                        ->description('Buat halaman-halaman default yang akan otomatis ter-clone saat tenant memilih tema ini.')
                        ->columnSpanFull()
                        ->schema([
                            Repeater::make('pages')
                                ->relationship('pages')
                                ->label('Pages in this Template')
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn(array $state): ?string => $state['title'] ?? null)
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('title')
                                            ->label('Page Title')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                                if ($state) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->required(),
                                    ]),
                                    Fieldset::make('SEO Settings')
                                        ->statePath('meta.seo')
                                        ->columns(1)
                                        ->schema([
                                            TextInput::make('title')->label('SEO Title')->required(),
                                            Textarea::make('description')->label('SEO Description')->rows(2)->required(),
                                            TagsInput::make('keywords')->label('Keywords'),
                                            TextInput::make('ogImage')->label('OG Image URL (Or Upload)'),
                                        ]),
                                    Builder::make('content_blocks')
                                        ->label('')
                                        ->collapsed()
                                        ->columns(2)
                                        ->blocks([
                                            // --- PROMOTIONS BLOCK ---
                                            Block::make('promotions')
                                                ->label('Promotions Section')
                                                ->icon('heroicon-o-megaphone')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('title')->label('Section Title')->default('Announcements'),
                                                    Repeater::make('items')
                                                        ->label('Promotion Items')
                                                        ->schema([
                                                            TextInput::make('id')->label('ID')->required(),
                                                            TextInput::make('title')->label('Judul')->required(),
                                                            Textarea::make('description')->label('Deskripsi'),
                                                        ])->collapsible()
                                                ])->columns(2),

                                            // --- HERO BLOCK ---
                                            Block::make('hero')
                                                ->label('Hero Section')
                                                ->icon('heroicon-o-stop')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('headline')->label('Headline')->required(),
                                                    TextInput::make('subheadline')->label('Sub Headline'),
                                                    TextInput::make('badge')->label('Badge Text'),
                                                    Fieldset::make('Call to Action (CTA)')
                                                        ->statePath('cta.primary')
                                                        ->columns(2)
                                                        ->schema([
                                                            TextInput::make('label')->label('Button Label'),
                                                            TextInput::make('href')->label('Button Link'),
                                                        ]),
                                                    Fieldset::make('Background Image')
                                                        ->statePath('backgroundImage')
                                                        ->columns(2)
                                                        ->schema([
                                                            TextInput::make('src')->label('Image URL or Uploaded path')->required(),
                                                            TextInput::make('alt')->label('Alt Text'),
                                                        ]),
                                                ])->columns(2),

                                            // --- ABOUT BLOCK ---
                                            Block::make('about')
                                                ->label('About Section')
                                                ->icon('heroicon-o-information-circle')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('title')->label('Judul')->required(),
                                                    Textarea::make('story')->label('Story')->rows(3),
                                                    TextInput::make('foundedYear')->label('Founded Year')->numeric(),
                                                    Fieldset::make('Image')
                                                        ->statePath('image')
                                                        ->columns(2)
                                                        ->schema([
                                                            TextInput::make('src')->label('Image URL')->required(),
                                                            TextInput::make('alt')->label('Alt Text'),
                                                        ]),
                                                    Repeater::make('highlights')
                                                        ->label('Highlights (Stats)')
                                                        ->schema([
                                                            TextInput::make('icon')->label('Icon (e.g. star)'),
                                                            TextInput::make('value')->label('Value'),
                                                            TextInput::make('label')->label('Label'),
                                                            TextInput::make('suffix')->label('Suffix (e.g. +)'),
                                                        ])->columns(2)->collapsible()
                                                ])->columns(2),

                                            // --- MENU BLOCK ---
                                            Block::make('menu')
                                                ->label('Menu Section')
                                                ->icon('heroicon-o-book-open')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('title')->label('Judul')->required(),
                                                    TextInput::make('ctaLabel')->label('CTA Label'),
                                                    TextInput::make('ctaHref')->label('CTA Link'),
                                                    Repeater::make('categories')
                                                        ->label('Categories')
                                                        ->schema([
                                                            TextInput::make('id')->label('Category ID')->required(),
                                                            TextInput::make('name')->label('Category Name')->required(),
                                                        ])->columns(2)->collapsible(),
                                                    Repeater::make('items')
                                                        ->label('Menu Items')
                                                        ->schema([
                                                            TextInput::make('id')->label('Item ID')->required(),
                                                            TextInput::make('categoryId')->label('Category ID')->required(),
                                                            TextInput::make('name')->label('Nama')->required(),
                                                            Textarea::make('description')->label('Deskripsi')->columnSpanFull(),
                                                            TextInput::make('price')->label('Price')->numeric()->required(),
                                                            TextInput::make('currency')->label('Currency')->default('USD'),
                                                            TagsInput::make('tags')->label('Tags'),
                                                            Toggle::make('featured')->label('Featured'),
                                                            Fieldset::make('Image')
                                                                ->statePath('image')
                                                                ->columns(2)
                                                                ->schema([
                                                                    TextInput::make('src')->label('Image URL'),
                                                                    TextInput::make('alt')->label('Alt Text'),
                                                                ]),
                                                        ])->columns(2)->collapsible(),
                                                ])->columns(2),

                                            // --- GALLERY BLOCK ---
                                            Block::make('gallery')
                                                ->label('Gallery Section')
                                                ->icon('heroicon-o-photo')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('title')->label('Judul')->required(),
                                                    Select::make('layout')->label('Layout')->options(['masonry' => 'Masonry', 'grid' => 'Grid'])->default('masonry'),
                                                    Repeater::make('images')
                                                        ->label('Images')
                                                        ->schema([
                                                            TextInput::make('src')->label('Image URL')->required(),
                                                            TextInput::make('alt')->label('Alt Text'),
                                                        ])->columns(2)->collapsible()
                                                ])->columns(2),

                                            // --- TESTIMONIALS BLOCK ---
                                            Block::make('testimonials')
                                                ->label('Testimonials Section')
                                                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('title')->label('Judul')->required(),
                                                    TextInput::make('averageRating')->label('Average Rating')->numeric()->step(0.1),
                                                    TextInput::make('totalReviews')->label('Total Reviews')->numeric(),
                                                    Repeater::make('items')
                                                        ->label('Reviews')
                                                        ->schema([
                                                            TextInput::make('id')->label('ID'),
                                                            TextInput::make('author')->label('Penulis')->required(),
                                                            TextInput::make('role')->label('Peran'),
                                                            TextInput::make('rating')->label('Rating')->numeric()->maxValue(5),
                                                            Textarea::make('content')->label('Review Content')->required()->columnSpanFull(),
                                                        ])->columns(2)->collapsible()
                                                ])->columns(2),

                                            // --- LOCATION BLOCK ---
                                            Block::make('location')
                                                ->label('Location Section')
                                                ->icon('heroicon-o-map-pin')
                                                ->schema([
                                                    Toggle::make('enabled')->label('Enable Section')->default(true),
                                                    TextInput::make('phone')->label('Phone Number'),
                                                    TextInput::make('email')->label('Email Address'),
                                                    Textarea::make('mapEmbedUrl')->label('Google Maps Embed URL')->columnSpanFull(),
                                                    Fieldset::make('Address')
                                                        ->statePath('address')
                                                        ->columns(2)
                                                        ->schema([
                                                            TextInput::make('street')->label('Street'),
                                                            TextInput::make('city')->label('City'),
                                                            TextInput::make('postalCode')->label('Postal Code'),
                                                            TextInput::make('country')->label('Country'),
                                                        ]),
                                                    Repeater::make('hours')
                                                        ->label('Operating Hours')
                                                        ->schema([
                                                            TextInput::make('day')->label('Day (e.g. monday)')->required(),
                                                            TextInput::make('open')->label('Open Time'),
                                                            TextInput::make('close')->label('Close Time'),
                                                            Toggle::make('closed')->label('Is Closed?'),
                                                        ])->columns(4)->collapsible()
                                                ])->columns(2),

                                            // --- RAW PAYLOAD (Fallback) ---
                                            Block::make('raw_payload')
                                                ->label('Custom Theme Config (JSON)')
                                                ->icon('heroicon-o-code-bracket')
                                                ->schema([
                                                    Placeholder::make('info')
                                                        ->label('Notice')
                                                        ->content('This block contains advanced raw JSON configuration for this page. Used mainly by seeders.'),
                                                    CodeEditor::make('content')
                                                        ->label('Raw JSON Configuration')
                                                        ->columnSpanFull()
                                                ]),
                                        ])
                                        ->collapsible()
                                        ->cloneable()
                                        ->blockNumbers(false),
                                ])->defaultItems(1)->reorderableWithButtons()->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull()
        ];
    }
}
