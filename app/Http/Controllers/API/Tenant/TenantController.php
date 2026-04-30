<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TenantRequest\GetTenantRequest;
use App\Http\Requests\API\TenantRequest\StoreTenantRequest;
use App\Http\Resources\API\Tenant\GetTenantResource;

class TenantController extends Controller
{
    public function get(GetTenantRequest $request)
    {
        $tenant = app('GetTenantService')->execute($request->all());

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
        $tenant = app('StoreTenantService')->execute($request->all());

        return response()->json([
            'success' => ( isset($tenant['error']) ? false : true ),
            'message' => $tenant['message'],
            'data' => $tenant['data'],
        ], $tenant['response_code']);
    }
}
