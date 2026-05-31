<?php

namespace App\Filament\Resources\Tenant\Tenants\Pages;

use App\Filament\Resources\Tenant\Tenants\TenantResource;
use App\Models\System\File;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Removed View and Delete actions to only allow them from the table list.
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $disk = 'public';

        $currentLogoPath = $record->logo?->file_path;
        $newLogoPath = $data['logo_upload'] ?? null;

        if ($newLogoPath !== $currentLogoPath) {
            if ($newLogoPath) {
                // User Upload Baru
                $logoFile = File::create([
                    'file_path'      => $newLogoPath,
                    'file_name'      => basename($newLogoPath),
                    'original_name'  => basename($newLogoPath),
                    'file_extension' => pathinfo($newLogoPath, PATHINFO_EXTENSION),
                    'mime_type'      => Storage::disk($disk)->mimeType($newLogoPath),
                    'file_size'      => Storage::disk($disk)->size($newLogoPath),
                    'storage_disk'   => $disk,
                    'tenant_id'      => $record->id,
                    'related_id'     => $record->id,
                    'related_type'   => get_class($record),
                    'is_public'      => 1,
                    'is_used'        => 1,
                ]);
                $data['logo_id'] = $logoFile->id;
            } else {
                $data['logo_id'] = null;
            }
        }

        $currentFaviconPath = $record->favicon?->file_path;
        $newFaviconPath = $data['favicon_upload'] ?? null;

        if ($newFaviconPath !== $currentFaviconPath) {
            if ($newFaviconPath) {
                $faviconFile = File::create([
                    'file_path'      => $newFaviconPath,
                    'file_name'      => basename($newFaviconPath),
                    'original_name'  => basename($newFaviconPath),
                    'file_extension' => pathinfo($newFaviconPath, PATHINFO_EXTENSION),
                    'mime_type'      => Storage::disk($disk)->mimeType($newFaviconPath),
                    'file_size'      => Storage::disk($disk)->size($newFaviconPath),
                    'storage_disk'   => $disk,
                    'tenant_id'      => $record->id,
                    'related_id'     => $record->id,
                    'related_type'   => get_class($record),
                    'is_public'      => 1,
                    'is_used'        => 1,
                ]);
                $data['favicon_id'] = $faviconFile->id;
            } else {
                $data['favicon_id'] = null;
            }
        }

        unset($data['logo_upload'], $data['favicon_upload']);

        return $data;
    }
}
