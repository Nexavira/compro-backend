<?php

namespace App\Http\Controllers\Api\V1\Portal\Cms;

use App\Http\Controllers\Controller;
use App\Models\GlobalTemplate;
use Illuminate\Http\Request;

class GlobalTemplateController extends Controller
{
    public function get(Request $request)
    {
        $result = app('GetGlobalTemplateService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $result['data']
        ]);
    }

    public function create(Request $request)
    {
        $result = app('StoreGlobalTemplateService')->execute($request->all());

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['response_code']);
    }

}
