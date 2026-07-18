<?php

namespace App\Observers;

use App\Models\Transaction\Payment;
use App\Models\Auth\UserDetail;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeTenantMail;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status') && $payment->status === 'paid') {
            // 1. Activate Subscription
            $subscription = $payment->subscription;
            $tenant = $payment->tenant;

            if ($subscription) {
                $subscription->status = 'active';
                $baseDate = $payment->due_date ?? now();
                $subscription->next_billing_date = $subscription->billing_cycle === 'annually'
                    ? $baseDate->copy()->addYearNoOverflow()
                    : $baseDate->copy()->addMonthNoOverflow();
                $subscription->save();

                // Create next payment invoice immediately if it doesn't already exist
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

            // 2. Activate Tenant & Trigger Template Cloning
            $tenant = $payment->tenant;
            if ($tenant) {
                $tenant->is_suspended = 0;
                $tenant->save();

                // Trigger Template Cloning after payment is paid (if it hasn't been cloned yet)
                // Note: We use the globalTemplate assigned during tenant creation.
                if ($tenant->global_template_id && $tenant->globalTemplate) {
                    $cloningService = new \App\Services\TemplateCloningService();
                    $cloningService->cloneTemplateToTenant($tenant->globalTemplate, $tenant);
                }
            }

            // 3. Send Welcome Email (Only for the first payment of the tenant)
            $isFirstPayment = !Payment::where('tenant_id', $payment->tenant_id)
                ->where('id', '<', $payment->id)
                ->exists();

            if ($isFirstPayment && $tenant) {
                $adminDetail = UserDetail::where('tenant_id', $payment->tenant_id)->first();
                if ($adminDetail && $adminDetail->user) {
                    $user = $adminDetail->user;
                    $token = Password::createToken($user);

                    // Construct reset url
                    $resetUrl = url('/admin/password-reset/' . $token . '?email=' . urlencode($user->email));

                    // Use nexavira26@gmail.com in local/development environment for testing
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

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
