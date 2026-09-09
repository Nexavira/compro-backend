<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\TenantRequest\GetTenantRequest;
use App\Http\Requests\Api\V1\Dashboard\TenantRequest\StoreTenantRequest;
use App\Http\Resources\Api\V1\Dashboard\Tenant\GetTenantResource;

class TenantController extends Controller
{
    public function get(GetTenantRequest $request)
    {
        $tenant = app('GetTenantService')->execute($request->validated());

        $data = null;
        if (isset($tenant['data'])) {
            $data = ( isset($tenant['data']->id)) ? new GetTenantResource($tenant['data']) :
            GetTenantResource::collection($tenant['data']);
        }

        return response()->json([
            'success' => ( isset($tenant['error']) ? false : true ),
            'message' => $tenant['message'],
            'data' => $data,
            'pagination' => $tenant['pagination'] ?? null
        ], $tenant['response_code']);
    }

    public function store(StoreTenantRequest $request)
    {
        $tenant = app('StoreTenantService')->execute($request->validated());

        return response()->json([
            'success' => ( isset($tenant['error']) ? false : true ),
            'message' => $tenant['message'],
            'data' => $tenant['data'],
        ], $tenant['response_code']);
    }
}
