<?php

namespace App\Http\Controllers\Api\V1\Portal\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Cms\GlobalTemplate\GetGlobalTemplateRequest;
use App\Http\Requests\Api\V1\Portal\Cms\GlobalTemplate\StoreGlobalTemplateRequest;
use App\Http\Resources\Api\V1\Portal\Cms\GlobalTemplate\GetGlobalTemplateResource;

class GlobalTemplateController extends Controller
{
    public function get(GetGlobalTemplateRequest $request)
    {
        $result = app('GetGlobalTemplateService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetGlobalTemplateResource($result['data']) :
                GetGlobalTemplateResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function create(StoreGlobalTemplateRequest $request)
    {
        $result = app('StoreGlobalTemplateService')->execute($request->validated());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }
}
