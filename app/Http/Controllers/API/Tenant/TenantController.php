<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TenantRequest\GetTenantRequest;
use App\Http\Resources\API\Tenant\GetTenantResource;

class TenantController extends Controller
{
    public function get(GetTenantRequest $request)
    {
        $role = app('GetTenantService')->execute($request->all());

        $data = null;
        if (isset($role['data'])) {
            $data = ( isset($role['data']->id)) ? new GetTenantResource($role['data']) :
            GetTenantResource::collection($role['data']);
        }

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $data,
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }

    public function store(StoreTenantRequest $request)
    {
        $role = app('StoreTenantService')->execute([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
        ], $role['response_code']);
    }
}
