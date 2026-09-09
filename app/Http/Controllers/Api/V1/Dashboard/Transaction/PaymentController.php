<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\Transaction\Payment\GetPaymentRequest;
use App\Http\Requests\Api\V1\Dashboard\Transaction\Payment\UploadPaymentProofRequest;
use App\Http\Resources\Api\V1\Dashboard\Transaction\Payment\GetPaymentResource;

class PaymentController extends Controller
{
    public function get(GetPaymentRequest $request)
    {
        $result = app('GetPaymentService')->execute($request->validated());

        $data = null;
        if (isset($result['data'])) {
            $data = (isset($result['data']->id)) ? new GetPaymentResource($result['data']) :
                GetPaymentResource::collection($result['data']);
        }

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data'    => $data,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }

    public function updateProof(UploadPaymentProofRequest $request)
    {
        $result = app('UploadPaymentProofService')->execute($request->validated());

        $data = isset($result['data']) ? new GetPaymentResource($result['data']) : null;

        return response()->json([
            'success' => isset($result['error']) ? false : true,
            'message' => $result['message'],
            'data' => $data,
        ], $result['response_code'] ?? 200);
    }
}
