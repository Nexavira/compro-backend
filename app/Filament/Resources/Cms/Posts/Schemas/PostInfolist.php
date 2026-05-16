<?php

namespace App\Filament\Resources\Cms\Posts\Schemas;

use App\Models\Cms\Post;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('tenant_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('body')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('is_active')
                    ->numeric(),
                TextEntry::make('version')
                    ->numeric(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->numeric()
                    ->visible(fn (Post $record): bool => $record->trashed()),
            ]);
    }
}
