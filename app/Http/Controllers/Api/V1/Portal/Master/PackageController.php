<?php

namespace App\Http\Controllers\Api\V1\Portal\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Master\PackageRequest\GetPackageRequest;
use App\Http\Resources\Api\V1\Portal\Master\Package\GetPackageResource;

class PackageController extends Controller
{
    public function get(GetPackageRequest $request)
    {
        $package = app('GetPackageService')->execute($request->all());

        $data = null;
        if (isset($package['data'])) {
            $data = (isset($package['data']->id)) ? new GetPackageResource($package['data']) :
                GetPackageResource::collection($package['data']);
        }

        return response()->json([
            'success' => (isset($package['error']) ? false : true),
            'message' => $package['message'],
            'data' => $data,
            'pagination' => $package['pagination'] ?? null
        ], $package['response_code']);
    }
}
