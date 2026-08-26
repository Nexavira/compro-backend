<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;
use App\Services\System\UploadFileService;

class RegisterSystemService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('UploadFileService', UploadFileService::class);
    }
}
