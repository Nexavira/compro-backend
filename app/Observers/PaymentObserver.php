<?php

namespace App\Observers;

use App\Models\Transaction\Payment;
use App\Models\Auth\DetailUser;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeTenantMail;
use App\Models\Tenant\TenantUser;
use Illuminate\Support\Facades\Log;
use App\Services\TemplateCloningService;

class PaymentObserver
{

    public function created(Payment $payment): void {}

    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status') && $payment->status === 'paid') {

            $subscription = $payment->subscription;
            $tenant = $payment->tenant;

            if ($subscription) {
                $subscription->status = 'active';
                $baseDate = $payment->due_date ?? now();
                $subscription->next_billing_date = $subscription->billing_cycle === 'annually'
                    ? $baseDate->copy()->addYearNoOverflow()
                    : $baseDate->copy()->addMonthNoOverflow();
                $subscription->start_at = now();
                $subscription->end_at = $subscription->next_billing_date;
                $subscription->save();

                if ($tenant) {
                    $existingPayment = Payment::where('tenant_id', $tenant->id)
                        ->where('subscription_id', $subscription->id)
                        ->whereDate('due_date', $subscription->next_billing_date)
                        ->where('status', 'unpaid')
                        ->exists();

                    if (!$existingPayment) {
                        $paymentCount = Payment::where('tenant_id', $tenant->id)->count();
                        $sequenceStr = str_pad($paymentCount + 1, 3, '0', STR_PAD_LEFT);
                        $monthStr = $subscription->next_billing_date->format('m');
                        $yearStr = $subscription->next_billing_date->format('Y');
                        $invoiceNumber = "INV-{$sequenceStr}/{$tenant->code}/{$monthStr}/{$yearStr}";

                        Payment::create([
                            'tenant_id' => $tenant->id,
                            'subscription_id' => $subscription->id,
                            'invoice_number' => $invoiceNumber,
                            'description' => 'Pembayaran Langganan ' . $subscription->package_name,
                            'amount_due' => $subscription->amount,
                            'due_date' => $subscription->next_billing_date,
                            'status' => 'unpaid',
                            'is_active' => 1,
                            'version' => 0,
                        ]);
                    }
                }
            }

            $tenant = $payment->tenant;
            if ($tenant) {
                $tenant->is_suspended = 0;
                $tenant->save();

                if ($tenant->global_template_id && $tenant->globalTemplate) {
                    $cloningService = new TemplateCloningService();
                    $cloningService->cloneTemplateToTenant($tenant->globalTemplate, $tenant);
                }
            }

            $isFirstPayment = !Payment::where('tenant_id', $payment->tenant_id)
                ->where('id', '<', $payment->id)
                ->exists();

            if ($isFirstPayment && $tenant) {
                $tenantUser = TenantUser::where('tenant_id', $payment->tenant_id)->first();
                if ($tenantUser && $tenantUser->user) {
                    $user = $tenantUser->user;
                    $token = Password::createToken($user);

                    $resetUrl = url('/admin/password-reset/' . $token . '?email=' . urlencode($user->email));

                    $recipientEmail = app()->environment('production') ? $user->email : 'nexavira26@gmail.com';

                    try {
                        Mail::to($recipientEmail)->send(new WelcomeTenantMail($tenant, $user, $resetUrl));
                    } catch (\Exception $e) {
                        Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    public function deleted(Payment $payment): void {}

    public function restored(Payment $payment): void {}

    public function forceDeleted(Payment $payment): void {}
}
