<?php

namespace App\Http\Controllers\Api\V1\Dashboard\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
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
