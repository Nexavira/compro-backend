<?php

namespace App\Http\Controllers\Api\V1\Portal\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Tenant\GetTenantCategoryRequest;
use App\Http\Resources\Api\V1\Portal\Tenant\GetTenantCategoryResource;

class TenantCategoryController extends Controller
{
    public function index(GetTenantCategoryRequest $request)
    {
        $result = app('GetTenantCategoryService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetTenantCategoryResource($result['data']) :
                GetTenantCategoryResource::collection($result['data']);
        }

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code']);
    }
}
