<?php

namespace App\Services\SystemService;

use App\Models\System\File;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UploadFileService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $file = $dto['file'];
        
        $sys_file = new File();

        $sys_file->tenant_id = $dto['tenant_id'];
        $sys_file->related_id = $dto['related_id'] ?? null;
        $sys_file->related_type = $dto['related_type'] ?? null;
        
        $path = $file->store('uploads/tenants/' . $dto['tenant_id'], 'public');

        $sys_file->file_name = basename($path);
        $sys_file->original_name = $file->getClientOriginalName();
        $sys_file->file_path = $path;
        $sys_file->mime_type = $file->getClientMimeType();
        $sys_file->file_extension = $file->extension();
        $sys_file->file_size = $file->getSize();
        $sys_file->storage_disk = 'public';
        $sys_file->is_public = $dto['is_public'] ?? 1;

        $this->prepareAuditActive($sys_file);
        $this->prepareAuditInsert($sys_file);
        
        $sys_file->save();

        $this->results['data'] = $sys_file;
        $this->results['message'] = "File successfully uploaded";
    }

    public function rules($dto)
    {
        return [
            'file' => ['required', 'file'],
            'tenant_id' => ['required', 'integer'],
            'related_id' => ['nullable', 'integer'],
            'related_type' => ['nullable', 'string'],
            'is_public' => ['nullable', 'integer'],
        ];
    }
}
