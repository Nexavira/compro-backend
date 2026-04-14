<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PermissionRequest\GetPermissionRequest;
use App\Http\Requests\API\PermissionRoleRequest\UpdatePermissionRoleRequest;
use App\Http\Resources\API\Admin\Permission\GetPermissionResource;

class PermissionController extends Controller
{
    public function get(GetPermissionRequest $request)
    {
        $permission = app('GetPermissionService')->execute($request->all());

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
        $permission = app('UpdatePermissionRoleService')->execute($request->all());

        return response()->json([
            'success' => (isset($permission['error']) ? false : true),
            'message' => $permission['message'],
            'data' => $permission['data'],
        ], $permission['response_code']);
    }
}
