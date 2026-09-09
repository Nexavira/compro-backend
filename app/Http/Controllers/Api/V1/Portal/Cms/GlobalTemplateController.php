<?php

namespace App\Http\Controllers\Api\V1\Portal\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\GlobalTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Portal\Cms\GlobalTemplate\GetGlobalTemplateRequest;
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

    public function create(Request $request)
    {
        $result = app('StoreGlobalTemplateService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }
}
