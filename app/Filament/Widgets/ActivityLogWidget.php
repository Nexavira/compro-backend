<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use App\Models\Auth\User;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Collection;

class ActivityLogWidget extends BaseWidget
{
    // HAPUS baris protected static string $view = ...; (Karena kita pakai TableBuilder sekarang)
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    // Opsional: Beri judul tabel
    protected static ?string $heading = 'Log Aktivitas Sistem Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()
            )
            ->columns([
                IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'success' => 'heroicon-o-plus-circle',
                        'danger' => 'heroicon-o-no-symbol',
                        'info' => 'heroicon-o-paint-brush',
                        default => 'heroicon-o-information-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'danger' => 'danger',
                        'info' => 'info',
                        default => 'gray',
                    }),
                
                TextColumn::make('aktivitas')
                    ->label('Aktivitas')
                    ->weight('bold')
                    ->searchable(),
                
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->wrap()
                    ->color('gray'),
                
                TextColumn::make('waktu')
                    ->label('Waktu')
                    ->alignment('right')
                    ->size('sm')
                    ->color('gray'),
            ])
            ->paginated(false); 
    }
}