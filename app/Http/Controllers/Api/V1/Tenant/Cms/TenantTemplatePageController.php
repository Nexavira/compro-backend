<?php

namespace App\Http\Controllers\Api\V1\Tenant\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplatePage\GetTenantTemplatePageRequest;
use App\Http\Resources\Api\V1\Tenant\Cms\TenantTemplatePage\GetTenantTemplatePageResource;

class TenantTemplatePageController extends Controller
{
    public function get(GetTenantTemplatePageRequest $request, $tenant_slug = null, $slug = null)
    {
        $payload = $request->validated();
        if ($tenant_slug) {
            $payload['tenant_slug'] = $tenant_slug;
        }
        if ($slug) {
            $payload['slug'] = $slug;
        }

        $result = app('PublicGetTenantTemplatePageService')->execute($payload);

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetTenantTemplatePageResource($result['data']) :
                GetTenantTemplatePageResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
        ], $result['response_code'] ?? 200);
    }
}
