<?php

namespace App\Filament\Widgets;

use App\Models\Auth\User;
use Filament\Tables;
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
            // 1. Panggil query langsung dari model Sushi kita
            ->query(
                \App\Models\ActivityLog::query()
            )
            // 2. Kolom tabel tetap sama seperti sebelumnya
            ->columns([
                Tables\Columns\IconColumn::make('status')
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
                
                Tables\Columns\TextColumn::make('aktivitas')
                    ->label('Aktivitas')
                    ->weight('bold')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->wrap()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->alignment('right')
                    ->size('sm')
                    ->color('gray'),
            ])
            ->paginated(false); 
    }
}