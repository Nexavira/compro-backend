<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\PermissionRequest\GetPermissionRequest;
use App\Http\Requests\Api\V1\Dashboard\PermissionRoleRequest\UpdatePermissionRoleRequest;
use App\Http\Resources\Api\V1\Dashboard\Permission\GetPermissionResource;

class PermissionController extends Controller
{
    public function get(GetPermissionRequest $request)
    {
        $permission = app('GetPermissionService')->execute($request->validated());

        $data = null;
        if (isset($permission['data'])) {
            $data = (isset($permission['data']->id)) ? new GetPermissionResource($permission['data']) :
            GetPermissionResource::collection($permission['data']);
        }

        return response()->json([
            'success' => (isset($permission['error']) ? false : true),
            'message' => $permission['message'],
            'data' => $data,
            'pagination' => $permission['pagination'] ?? null,
        ], $permission['response_code']);
    }

    public function updatePermissionRole(UpdatePermissionRoleRequest $request)
    {
        $permission = app('UpdatePermissionRoleService')->execute($request->validated());

        return response()->json([
            'success' => (isset($permission['error']) ? false : true),
            'message' => $permission['message'],
            'data' => $permission['data'],
        ], $permission['response_code']);
    }
}
