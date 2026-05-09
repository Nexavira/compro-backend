<?php

namespace App\Filament\Resources\Tenant\Tenants\Pages;

use App\Filament\Resources\Tenant\Tenants\TenantResource;
use App\Models\System\File;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['logo_upload']) && filled($data['logo_upload'])) {
            $path = is_array($data['logo_upload']) ? reset($data['logo_upload']) : $data['logo_upload'];
            
            $disk = 'public';
            $fileExists = Storage::disk($disk)->exists($path);

            $logoFile = File::create([
                'file_path'      => $path,
                'file_name'      => basename($path),
                'original_name'  => basename($path),
                'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                'storage_disk'   => $disk, 
                'is_public'      => 1,
                'is_used'        => 1,
            ]);

            $data['logo_id'] = $logoFile->id;
        }

        if (isset($data['favicon_upload']) && filled($data['favicon_upload'])) {
            $path = is_array($data['favicon_upload']) ? reset($data['favicon_upload']) : $data['favicon_upload'];
            
            $disk = 'public';
            $fileExists = Storage::disk($disk)->exists($path);

            $faviconFile = File::create([
                'file_path'      => $path,
                'file_name'      => basename($path),
                'original_name'  => basename($path),
                'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                'storage_disk'   => $disk,
                'is_public'      => 1,
                'is_used'        => 1,
            ]);

            $data['favicon_id'] = $faviconFile->id;
        }

        unset($data['logo_upload'], $data['favicon_upload']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $tenant = $this->record; 

        if ($tenant->logo_id) {
            File::where('id', $tenant->logo_id)->update([
                'tenant_id'    => $tenant->id,
                'related_id'   => $tenant->id,
                'related_type' => get_class($tenant),
            ]);
        }

        if ($tenant->favicon_id) {
            File::where('id', $tenant->favicon_id)->update([
                'tenant_id'    => $tenant->id,
                'related_id'   => $tenant->id,
                'related_type' => get_class($tenant),
            ]);
        }
    }
}
