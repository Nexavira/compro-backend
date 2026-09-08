<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate\GetTenantTemplateRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate\StoreTenantTemplateRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate\UpdateTenantTemplateRequest;
use App\Http\Resources\Api\V1\Dashboard\Cms\TenantTemplate\GetTenantTemplateResource;

class TenantTemplateController extends Controller
{
    public function get(GetTenantTemplateRequest $request)
    {
        $result = app('GetTenantTemplateService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetTenantTemplateResource($result['data']) :
                GetTenantTemplateResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function create(StoreTenantTemplateRequest $request)
    {
        if ($request->has('pages')) {
            $result = app('CreateTenantTemplateService')->execute($request->validated());
        } else {
            $result = app('StoreTenantTemplateService')->execute($request->validated());
        }

        $data = isset($result['data']) ? new GetTenantTemplateResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }

    public function update(UpdateTenantTemplateRequest $request)
    {
        if ($request->has('pages')) {
            $result = app('EditTenantTemplateService')->execute($request->validated());
        } else {
            $result = app('UpdateTenantTemplateService')->execute($request->validated());
        }

        $data = isset($result['data']) ? new GetTenantTemplateResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }
}
