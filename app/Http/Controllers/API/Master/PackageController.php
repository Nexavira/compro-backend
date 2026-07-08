<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Package\GetPackageResource;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function get(Request $request)
    {
        $tenant = app('GetPackageService')->execute($request->all());

        // $data = null;
        // if (isset($tenant['data'])) {
        //     $data = ( isset($tenant['data']->id)) ? new GetPackageResource($tenant['data']) :
        //     GetPackageResource::collection($tenant['data']);
        // }

        return response()->json([
            'success' => ( isset($tenant['error']) ? false : true ),
            'message' => $tenant['message'],
            'data' => $tenant['data'],
            'pagination' => $tenant['pagination'] ?? null
        ], $tenant['response_code']);
    }
}
