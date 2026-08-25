<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\TenantCategoryRequest\GetTenantCategoryRequest;
use App\Http\Resources\Api\V1\Dashboard\TenantCategory\GetTenantCategoryResource;

class TenantCategoryController extends Controller
{
    public function get(GetTenantCategoryRequest $request)
    {
        $tenant_category = app('GetTenantCategoryService')->execute($request->all());

        $data = null;
        if (isset($tenant_category['data'])) {
            $data = ( isset($tenant_category['data']->id)) ? new GetTenantCategoryResource($tenant_category['data']) :
            GetTenantCategoryResource::collection($tenant_category['data']);
        }

        return response()->json([
            'success' => ( isset($tenant_category['error']) ? false : true ),
            'message' => $tenant_category['message'],
            'data' => $data,
            'pagination' => $tenant_category['pagination'] ?? null
        ], $tenant_category['response_code']);
    }
}
