<?php

namespace App\Filament\Resources\Cms\GlobalTemplates\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class GlobalTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->schema([
                    Grid::make(1)
                        ->columnSpan(2)
                        ->schema([
                            Section::make('Template Details')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Template Name (e.g., SaaS Landing Page)')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                                            if ($operation === 'create' && $state) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),

                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->required()
                                        ->unique(ignoreRecord: true),

                                    Textarea::make('description')
                                        ->label('Template Description')
                                        ->placeholder('Explain the advantages of this template so that admin/tenant know its functionality...')
                                        ->columnSpanFull()
                                        ->rows(2),
                                ]),

                            Section::make('Default Design Block Layout (JSON Blueprint)')
                                ->description('Arrange the initial layout that will be received by the tenant when selecting this template.')
                                ->schema([
                                    Builder::make('content_blocks')
                                        ->label('')
                                        ->blocks([
                                            Block::make('hero_section')
                                                ->label('Hero Section (Header)')
                                                ->icon('heroicon-o-stop')
                                                ->schema([
                                                    TextInput::make('heading')->label('Default Main Title')->required(),
                                                    Textarea::make('subheading')->label('Default Sub Title')->rows(2),
                                                    TextInput::make('button_text')->label('Button Text'),
                                                    TextInput::make('button_link')->label('Button Link'),
                                                    FileUpload::make('background_image')
                                                        ->label('Example Background Image')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('cms/templates/hero'),
                                                ]),

                                            Block::make('rich_text')
                                                ->label('Teks Konten (Rich Text)')
                                                ->icon('heroicon-o-document-text')
                                                ->schema([
                                                    RichEditor::make('content')->label('Default Content')->required(),
                                                ]),

                                            Block::make('faq_section')
                                                ->label('Frequently Asked Questions')
                                                ->icon('heroicon-o-question-mark-circle')
                                                ->schema([
                                                    TextInput::make('section_title')->label('Default FAQ Section Title')->default('Frequently Asked Questions'),
                                                    Repeater::make('questions')
                                                        ->label('Example Questions')
                                                        ->schema([
                                                            TextInput::make('question')->label('Question')->required(),
                                                            Textarea::make('answer')->label('Answer')->required(),
                                                        ])
                                                        ->collapsible(),
                                                ]),
                                        ])
                                        ->collapsible()
                                        ->cloneable()
                                        ->blockNumbers(false),
                                ]),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Template Metrics')
                                ->schema([
                                    Select::make('category')
                                        ->label('Template Category')
                                        ->options([
                                            'company_profile' => 'Company Profile',
                                            'landing_page'    => 'Landing Page',
                                            'portfolio'       => 'Portfolio & Resume',
                                            'facility'        => 'Facility',
                                        ])
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->default('company_profile')
                                        ->required(),

                                    Select::make('is_active')
                                        ->label('Status Template')
                                        ->options([
                                            1 => 'Active (Available for Tenant)',
                                            0 => 'Inactive (Hide)',
                                        ])
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->default(1)
                                        ->required(),
                                ]),
                        ]),
                ])
                ->columnSpanFull()
        ]);
    }
}
