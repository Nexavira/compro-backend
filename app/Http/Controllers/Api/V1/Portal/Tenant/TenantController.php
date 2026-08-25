<?php

namespace App\Http\Controllers\Api\V1\Portal\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Tenant\CreateTenantRequest;

class TenantController extends Controller
{
    public function create(CreateTenantRequest $request)
    {
        $result = app('CreateTenantService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }
}
