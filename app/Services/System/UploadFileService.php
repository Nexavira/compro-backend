<?php

namespace App\Services\System;

use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UploadFileService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $file = $dto['file'];

        $sys_file = new File;

        $sys_file->tenant_id = $dto['tenant_id'] ?? null;
        $sys_file->related_id = $dto['related_id'] ?? null;
        $sys_file->related_type = $dto['related_type'] ?? null;

        $is_public = isset($dto['is_public']) ? (int) $dto['is_public'] : 1;
        $disk = $is_public === 1 ? 'public' : 'local';

        $pathPrefix = 'uploads/';
        $pathPrefix .= $sys_file->tenant_id ? 'tenants/' . $sys_file->tenant_id : 'general';

        $path = $file->store($pathPrefix, $disk);

        $sys_file->file_name = basename($path);
        $sys_file->original_name = $file->getClientOriginalName();
        $sys_file->file_path = $path;
        $sys_file->mime_type = $file->getClientMimeType();
        $sys_file->file_extension = $file->extension() ?: $file->getClientOriginalExtension();
        $sys_file->file_size = $file->getSize();
        $sys_file->storage_disk = $disk;
        $sys_file->is_public = $is_public;

        $this->prepareAuditActive($sys_file);
        $this->prepareAuditInsert($sys_file);

        $sys_file->save();

        $this->results['data'] = $sys_file;
        $this->results['message'] = "File successfully uploaded";
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid']) && $dto['tenant_uuid'] != '') {
            $tenant = Tenant::where('uuid', $dto['tenant_uuid'])->first();
            if ($tenant) {
                $dto['tenant_id'] = $tenant->id;
            }
        }
        return $dto;
    }

    public function rules($dto)
    {
        return [
            'file' => ['required', 'file', 'max:51200'],
            'tenant_id' => ['required', 'integer'],
            'related_id' => ['nullable', 'integer'],
            'related_type' => ['nullable', 'string'],
            'is_public' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
