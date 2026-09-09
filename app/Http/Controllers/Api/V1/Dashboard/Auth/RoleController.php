<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\RoleRequest\DeleteRoleRequest;
use App\Http\Requests\Api\V1\Dashboard\RoleRequest\GetRoleRequest;
use App\Http\Requests\Api\V1\Dashboard\RoleRequest\StoreRoleRequest;
use App\Http\Requests\Api\V1\Dashboard\RoleRequest\UpdateRoleRequest;
use App\Http\Resources\Api\V1\Dashboard\Role\GetRoleResource;

class RoleController extends Controller
{
    public function get(GetRoleRequest $request)
    {
        $role = app('GetRoleService')->execute($request->validated());

        $data = null;
        if (isset($role['data'])) {
            $data = ( isset($role['data']->id)) ? new GetRoleResource($role['data']) :
            GetRoleResource::collection($role['data']);
        }

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $data,
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = app('StoreRoleService')->execute([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
        ], $role['response_code']);
    }

    public function update (UpdateRoleRequest $request)
    {
        $role = app('UpdateRoleService')->execute($request->validated());

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
        ], $role['response_code']);
    }
    public function destroy (DeleteRoleRequest $request)
    {

        $role = app('DeleteRoleService')->execute($request->validated());

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }
}
