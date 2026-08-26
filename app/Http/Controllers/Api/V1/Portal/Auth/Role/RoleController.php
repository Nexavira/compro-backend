<?php

namespace App\Http\Controllers\Api\V1\Portal\Auth\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Auth\Role\GetRoleRequest;
use App\Http\Resources\Api\V1\Portal\Auth\Role\GetRoleResource;

class RoleController extends Controller
{
    public function get(GetRoleRequest $request)
    {
        $role = app('GetRoleService')->execute($request->all());

        $data = null;
        if (isset($role['data'])) {
            $data = (isset($role['data']->id)) ? new GetRoleResource($role['data']) :
                GetRoleResource::collection($role['data']);
        }

        return response()->json([
            'success' => (isset($role['error']) ? false : true),
            'message' => $role['message'],
            'data' => $data,
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }
}
