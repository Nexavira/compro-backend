<?php

namespace App\Http\Controllers\Api\V1\Portal\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\System\UploadFileRequest;
use App\Http\Requests\Api\V1\Portal\System\GetFileRequest;
use App\Http\Resources\Api\V1\Portal\System\GetFileResource;

class FileController extends Controller
{
    public function get(GetFileRequest $request)
    {
        $result = app('GetFileService')->execute($request->all());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetFileResource($result['data']) :
                GetFileResource::collection($result['data']);
        }

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code']);
    }

    public function upload(UploadFileRequest $request)
    {
        $dto = $request->validated();

        if ($request->hasFile('file')) {
            $dto['file'] = $request->file('file');
        }

        $uploadFile = app('UploadFileService')->execute($dto);

        return response()->json([
            'success' => (!isset($uploadFile['error'])),
            'message' => $uploadFile['message'],
            'data' => $uploadFile['data'],
        ])->setStatusCode((isset($uploadFile['error']) ? 500 : 200));
    }
}
