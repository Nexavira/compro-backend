<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\GetTenantTemplatePageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\StoreTenantTemplatePageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\UpdateTenantTemplatePageRequest;
use App\Http\Resources\Api\V1\Dashboard\Cms\TenantTemplatePage\GetTenantTemplatePageResource;
use Illuminate\Http\Request;

class TenantTemplatePageController extends Controller
{
    public function get(GetTenantTemplatePageRequest $request)
    {
        $result = app('GetTenantTemplatePageService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetTenantTemplatePageResource($result['data']) :
                GetTenantTemplatePageResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function create(StoreTenantTemplatePageRequest $request)
    {
        $result = app('StoreTenantTemplatePageService')->execute($request->validated());

        $data = isset($result['data']) ? new GetTenantTemplatePageResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }

    public function update(UpdateTenantTemplatePageRequest $request)
    {
        $result = app('UpdateTenantTemplatePageService')->execute($request->validated());

        $data = isset($result['data']) ? new GetTenantTemplatePageResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }
}
