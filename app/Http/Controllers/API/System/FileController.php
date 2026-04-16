<?php

namespace App\Http\Controllers\API\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $dto = $request->all();
        // Since $request->all() doesn't always handle files properly depending on content type,
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
