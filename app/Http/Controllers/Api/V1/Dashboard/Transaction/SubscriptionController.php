<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\Transaction\Subscription\GetSubscriptionRequest;
use App\Http\Resources\Api\V1\Dashboard\Transaction\Subscription\GetSubscriptionResource;

class SubscriptionController extends Controller
{
    public function get(GetSubscriptionRequest $request)
    {
        $result = app('GetSubscriptionService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetSubscriptionResource($result['data']) :
                GetSubscriptionResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }
}
