<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;

class RegisterSystemService extends AppServiceProvider
{
    public function register(): void
    {
        $this->registerService('UploadFileService', \App\Services\SystemService\UploadFileService::class);
    }
}
