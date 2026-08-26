<?php

namespace App\Services\Auth;

use App\Services\DefaultService;
use App\Services\ServiceInterface;

class DoLogoutService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        if (auth()->check()) {
            auth()->user()->token()->revoke();
            $this->results['message'] = "Successfully logged out";
        } else {
            throw new \Exception('Unauthorized request', 401);
        }
    }
}
