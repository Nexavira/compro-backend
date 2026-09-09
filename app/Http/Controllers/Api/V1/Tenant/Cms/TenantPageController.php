<?php

namespace App\Http\Controllers\Api\V1\Tenant\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tenant\Cms\TenantPage\GetTenantPageRequest;
use App\Http\Resources\Api\V1\Tenant\Cms\TenantPage\GetGetTenantPageResource;

class TenantPageController extends Controller
{
    public function get(GetTenantPageRequest $request, $slug)
    {
        $payload = $request->validated();
        $payload['slug'] = $slug;

        $result = app('PublicGetTenantPageService')->execute($payload);

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetGetTenantPageResource($result['data']) :
                GetGetTenantPageResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
        ], $result['response_code'] ?? 200);
    }
}
