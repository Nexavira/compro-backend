<?php

namespace App\Filament\Resources\Cms\Posts\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class PostForm
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
                            Section::make('Article Content')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Title')
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
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule, callable $get) {
                                            return $rule->where('tenant_id', $get('tenant_id'));
                                        }),

                                    Textarea::make('content')
                                        ->label('Short Summary (Excerpt)')
                                        ->rows(3)
                                        ->helperText('A short text that appears on the front article list page.'),

                                    RichEditor::make('body')
                                        ->label('Full Article Content')
                                        ->toolbarButtons([
                                            'attachFiles', 'blockquote', 'bold', 'bulletList', 
                                            'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'undo',
                                        ])
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('cms/posts')
                                        ->required(),
                                ]),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Publishing Settings')
                                ->schema([
                                    Select::make('tenant_id')
                                        ->label('Tenant Ownership')
                                        ->relationship('tenant', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('is_active')
                                        ->label('Publishing Status')
                                        ->options([
                                            1 => 'Published',
                                            0 => 'Draft',
                                        ])
                                        ->default(1)
                                        ->required(),
                                ]),
                            Section::make('Metadata & SEO')
                                ->schema([
                                    KeyValue::make('metadata')
                                        ->label('Additional Data')
                                        ->keyLabel('Property Name (Example: author, seo_title)')
                                        ->valueLabel('Value')
                                        ->addActionLabel('Add Metadata'),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
