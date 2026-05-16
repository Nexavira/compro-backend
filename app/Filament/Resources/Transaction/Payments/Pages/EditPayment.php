<?php

namespace App\Filament\Resources\Transaction\Payments\Pages;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use App\Models\System\File;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $path = $data['proof_of_payment_upload'] ?? null;
        if (is_array($path)) {
            $path = reset($path);
        }

        $currentPath = $this->record->proofOfPayment?->file_path;
        $disk = 'public';

        if (!empty($path) && $path !== $currentPath) {
            $fileExists = Storage::disk($disk)->exists($path);

            $file = File::create([
                'file_path'      => $path,
                'file_name'      => basename($path),
                'original_name'  => basename($path),
                'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                'storage_disk'   => $disk,
                'tenant_id'      => $this->record->tenant_id,
                'related_id'     => $this->record->id,
                'related_type'   => get_class($this->record),
                'is_public'      => 1,
                'is_used'        => 1,
            ]);

            $data['proof_of_payment_id'] = $file->id;
        } elseif (empty($path) && $currentPath) {
            $data['proof_of_payment_id'] = null;
        }

        if (isset($data['status'])) {
            if ($data['status'] === 'paid') {
                $data['payment_date'] = $this->record->payment_date ?? now()->format('Y-m-d');
            } else {
                $data['payment_date'] = null;
            }
        }

        unset($data['proof_of_payment_upload']);

        return $data;
    }
}
