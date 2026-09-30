<?php

namespace App\Http\Controllers\Api\V1\Tenant\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplate\GetTenantTemplateRequest;
use App\Http\Resources\Api\V1\Tenant\Cms\TenantTemplate\GetTenantTemplateResource;

class TenantTemplateController extends Controller
{
    public function get(GetTenantTemplateRequest $request, $tenant_slug = null, $tenant_template_uuid = null)
    {
        $payload = $request->validated();
        if ($tenant_slug) {
            $payload['tenant_slug'] = $tenant_slug;
        }
        if ($tenant_template_uuid) {
            $payload['tenant_template_uuid'] = $tenant_template_uuid;
        }

        $result = app('GetTenantTemplateService')->execute($payload);

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
}
