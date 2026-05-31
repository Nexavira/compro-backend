<?php

namespace App\Filament\Resources\Cms\Pages\Schemas;

use App\Models\GlobalTemplate;
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
use Illuminate\Validation\Rules\Unique;

class PageForm
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
                            Section::make('Page Identity')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Page Title')
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'Page Title is required'
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
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, callable $get) {
                                            return $rule->where('tenant_id', $get('tenant_id'));
                                        }),
                                ]),

                            Section::make('Page Builder')
                                ->description('Add design blocks manually, or copy from a Global Template.')
                                ->schema([
                                    Select::make('global_template_id')
                                        ->label('Quick Start: Copy from Global Template')
                                        ->placeholder('Select a template to copy its layout...')
                                        ->options(function () {
                                            return GlobalTemplate::where('is_active', 1)->pluck('title', 'id');
                                        })
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, $state) {
                                            if ($state) {
                                                $template = GlobalTemplate::find($state);
                                                
                                                if ($template && $template->content_blocks) {
                                                    $set('content_blocks', $template->content_blocks);
                                                }
                                            }
                                        })
                                        ->hiddenOn('view')
                                        ->dehydrated(false),
                                    Builder::make('content_blocks')
                                        ->label('')
                                        ->blocks([
                                            Block::make('hero_section')
                                                ->label('Hero Section (Header)')
                                                ->icon('heroicon-o-stop')
                                                ->schema([
                                                    TextInput::make('heading')
                                                        ->label('Title')
                                                        ->markAsRequired()
                                                        ->rules(['required'])
                                                        ->validationMessages([
                                                            'required' => 'Title is required'
                                                        ]),
                                                    Textarea::make('subheading')->label('Sub Title')->rows(3),
                                                    TextInput::make('button_text')->label('Button Text (Optional)'),
                                                    TextInput::make('button_link')->label('Button Link'),
                                                    FileUpload::make('background_image')
                                                        ->label('Background Image')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('cms/pages/hero'),
                                                ]),
                                            Block::make('rich_text')
                                                ->label('Rich Text')
                                                ->icon('heroicon-o-document-text')
                                                ->schema([
                                                    RichEditor::make('content')
                                                        ->label('Content')
                                                        ->markAsRequired()
                                                        ->rules(['required'])
                                                        ->validationMessages([
                                                            'required' => 'Content is required'
                                                        ]),
                                                ]),
                                            Block::make('faq_section')
                                                ->label('Frequently Asked Questions')
                                                ->icon('heroicon-o-question-mark-circle')
                                                ->schema([
                                                    TextInput::make('section_title')->label('Section Title')->default('Frequently Asked Questions'),
                                                    Repeater::make('questions')
                                                        ->label('Question List')
                                                        ->schema([
                                                            TextInput::make('question')
                                                                ->label('Question')
                                                                ->markAsRequired()
                                                                ->rules(['required'])
                                                                ->validationMessages([
                                                                    'required' => 'Question is required'
                                                                ]),
                                                            Textarea::make('answer')
                                                                ->label('Answer')
                                                                ->markAsRequired()
                                                                ->rules(['required'])
                                                                ->validationMessages([
                                                                    'required' => 'Answer is required'
                                                                ]),
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
                            Section::make('Settings')
                                ->schema([
                                    Select::make('tenant_id')
                                        ->label('Tenant Ownership')
                                        ->relationship('tenant', 'name')
                                        ->native(false)
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes([
                                            'style' => 'cursor: pointer !important;',
                                        ])
                                        ->markAsRequired()
                                        ->rules(['required'])
                                        ->validationMessages([
                                            'required' => 'Tenant Ownership is required'
                                        ]),

                                    Select::make('is_active')
                                        ->label('Page Status')
                                        ->options([
                                            1 => 'Published',
                                            0 => 'Draft',
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
                                            'required' => 'Page Status is required'
                                        ]),
                                ]),
                        ]),
                ])
                ->columnSpanFull()
        ];
    }
}
