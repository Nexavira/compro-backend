<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantPage\GetTenantPageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantPage\StoreTenantPageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantPage\UpdateTenantPageRequest;
use App\Http\Resources\Api\V1\Dashboard\Cms\TenantPage\GetTenantPageResource;
use Illuminate\Http\Request;

class TenantPageController extends Controller
{
    public function get(GetTenantPageRequest $request)
    {
        $result = app('GetTenantPageService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetTenantPageResource($result['data']) :
                GetTenantPageResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function create(StoreTenantPageRequest $request)
    {
        $result = app('StoreTenantPageService')->execute($request->validated());

        $data = isset($result['data']) ? new GetTenantPageResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }

    public function update(UpdateTenantPageRequest $request)
    {
        $result = app('UpdateTenantPageService')->execute($request->validated());

        $data = isset($result['data']) ? new GetTenantPageResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }
}
