<?php

namespace App\Http\Controllers\Api\V1\Portal\Cms;

use App\Http\Controllers\Controller;
use App\Models\GlobalTemplate;
use Illuminate\Http\Request;

class GlobalTemplateController extends Controller
{
    public function get(Request $request)
    {
        $templates = GlobalTemplate::where('is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $templates
        ]);
    }
}
