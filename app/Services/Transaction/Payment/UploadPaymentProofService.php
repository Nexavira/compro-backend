<?php

namespace App\Services\Transaction\Payment;

use App\Models\Transaction\Payment;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;

class UploadPaymentProofService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $payment = Payment::where('uuid', $dto['payment_uuid'])->first();
        if (!$payment) {
            $this->results['error'] = true;
            $this->results['message'] = "Payment not found";
            $this->results['response_code'] = 404;
            return;
        }

        if ($payment->status === 'paid') {
            $this->results['error'] = true;
            $this->results['message'] = "Payment is already marked as paid";
            $this->results['response_code'] = 400;
            return;
        }

        DB::beginTransaction();
        try {
            $uploadDto = [
                'file' => $dto['proof_of_payment'],
                'tenant_id' => $payment->tenant_id,
                'related_id' => $payment->id,
                'related_type' => Payment::class,
                'is_public' => 0,
            ];

            $uploadResult = app('UploadFileService')->execute($uploadDto);

            if (isset($uploadResult['error'])) {
                DB::rollBack();
                $this->results['error'] = true;
                $this->results['message'] = $uploadResult['message'];
                $this->results['response_code'] = $uploadResult['response_code'] ?? 500;
                return;
            }

            $payment->proof_of_payment_id = $uploadResult['data']->id;
            $payment->status = 'pending_verification';
            $payment->payment_date = now();
            $payment->save();

            DB::commit();

            $this->results['data'] = $payment;
            $this->results['message'] = "Payment proof uploaded successfully";
            $this->results['response_code'] = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->results['error'] = true;
            $this->results['message'] = $e->getMessage();
            $this->results['response_code'] = 500;
        }
    }

    public function rules($dto)
    {
        return [
            // Validation is already handled in the Request, but we can return empty or add rules here if needed
        ];
    }
}
