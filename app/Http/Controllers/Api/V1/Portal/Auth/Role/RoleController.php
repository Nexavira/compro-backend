<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\Role\GetRoleRequest;
use App\Http\Resources\Api\V1\Portal\Auth\Role\GetRoleResource;

class RoleController extends Controller
{
    public function get(GetRoleRequest $request)
    {
        $result = app('GetRoleService')->execute($request->all());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetRoleResource($result['data']) :
                GetRoleResource::collection($result['data']);
        }

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code']);
    }
}
