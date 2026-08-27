<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\System\UploadFileService;
use App\Services\System\GetFileService;

class RegisterSystemService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('UploadFileService', UploadFileService::class);
        $this->registerService('GetFileService', GetFileService::class);
    }
}
