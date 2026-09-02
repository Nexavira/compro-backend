<?php

namespace App\Services\Auth;

use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetUserSessionInformationService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $user = auth()->user();

        if (!$user) {
            $this->results['error'] = true;
            $this->results['message'] = "User not found";
            $this->results['response_code'] = 404;
            return;
        }

        $this->results['data'] = $user;
        $this->results['message'] = "Session information retrieved successfully";
    }

    public function rules($dto)
    {
        return [];
    }
}
