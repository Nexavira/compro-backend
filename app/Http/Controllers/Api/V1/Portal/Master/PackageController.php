<?php

namespace App\Http\Controllers\Api\V1\Portal\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Master\PackageRequest\GetPackageRequest;
use App\Http\Resources\Api\V1\Portal\Master\Package\GetPackageResource;

class PackageController extends Controller
{
    public function get(GetPackageRequest $request)
    {
        $result = app('GetPackageService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetPackageResource($result['data']) :
                GetPackageResource::collection($result['data']);
        }

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code']);
    }
}
