<?php

namespace App\Http\Controllers\Api\V1\Portal\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Portal\Transaction\UploadPaymentProofRequest;

class TransactionController extends Controller
{
    public function uploadPaymentProof(UploadPaymentProofRequest $request, $payment_uuid)
    {
        $result = app('UploadPaymentProofService')->execute($request->validated());

        return response()->json([
            'success' => (isset($result['error']) ? false : true),
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
            'pagination' => $result['pagination'] ?? null
        ], $result['response_code'] ?? 200);
    }
}
