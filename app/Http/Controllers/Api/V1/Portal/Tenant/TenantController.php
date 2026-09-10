<?php

namespace App\Http\Controllers\Api\V1\Portal\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Tenant\CreateTenantRequest;
use App\Http\Requests\Api\V1\Portal\Tenant\EditTenantInformationRequest;

class TenantController extends Controller
{
    public function create(CreateTenantRequest $request)
    {
        $result = app('CreateTenantService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }

    public function editTenantInformation(EditTenantInformationRequest $request)
    {
        $result = app('EditTenantInformationService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }
}
