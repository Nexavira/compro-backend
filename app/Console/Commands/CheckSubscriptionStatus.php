<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction\Payment;
use App\Models\Tenant\TenantUser;
use App\Mail\InvoiceReminderMail;
use App\Mail\TenantSuspendedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-subscription-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription status and send reminders or suspend tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting subscription status check...');

        $today = Carbon::today();
        
        // Target reminder days: H-7, H-3, H-1
        $reminderDays = [7, 3, 1];
        
        // 1. Send Reminders for upcoming payments
        foreach ($reminderDays as $days) {
            $targetDate = $today->copy()->addDays($days)->format('Y-m-d');
            
            $payments = Payment::where('status', 'unpaid')
                ->whereDate('due_date', $targetDate)
                ->with(['tenant', 'subscription'])
                ->get();
                
            foreach ($payments as $payment) {
                if ($payment->tenant && $payment->tenant->is_suspended == 0) {
                    $this->sendReminderEmail($payment, $days);
                }
            }
        }
        
        // 2. Suspend tenants whose payment is past due
        $overduePayments = Payment::where('status', 'unpaid')
            ->whereDate('due_date', '<', $today->format('Y-m-d'))
            ->with(['tenant'])
            ->get();
            
        foreach ($overduePayments as $payment) {
            $tenant = $payment->tenant;
            if ($tenant && $tenant->is_suspended == 0) {
                $tenant->is_suspended = 1;
                $tenant->save();
                
                $this->sendSuspendedEmail($tenant);
                $this->info("Tenant {$tenant->name} suspended due to unpaid invoice {$payment->invoice_number}.");
            }
        }

        $this->info('Subscription status check completed.');
    }

    private function sendReminderEmail(Payment $payment, int $daysLeft)
    {
        $tenant = $payment->tenant;
        $tenantUser = TenantUser::where('tenant_id', $tenant->id)->first();
        
        if ($tenantUser && $tenantUser->user) {
            $user = $tenantUser->user;
            $recipientEmail = app()->environment('production') ? $user->email : 'nexavira26@gmail.com';

            try {
                Mail::to($recipientEmail)->send(new InvoiceReminderMail($tenant, $user, $payment, $daysLeft));
                $this->info("Reminder sent to {$recipientEmail} for tenant {$tenant->name} ({$daysLeft} days left).");
            } catch (\Exception $e) {
                Log::error("Failed to send reminder email to {$recipientEmail}: " . $e->getMessage());
                $this->error("Failed to send reminder email to {$recipientEmail}: " . $e->getMessage());
            }
        }
    }

    private function sendSuspendedEmail($tenant)
    {
        $tenantUser = TenantUser::where('tenant_id', $tenant->id)->first();
        
        if ($tenantUser && $tenantUser->user) {
            $user = $tenantUser->user;
            $recipientEmail = app()->environment('production') ? $user->email : 'nexavira26@gmail.com';

            try {
                Mail::to($recipientEmail)->send(new TenantSuspendedMail($tenant, $user));
                $this->info("Suspended notification sent to {$recipientEmail} for tenant {$tenant->name}.");
            } catch (\Exception $e) {
                Log::error("Failed to send suspended email to {$recipientEmail}: " . $e->getMessage());
                $this->error("Failed to send suspended email to {$recipientEmail}: " . $e->getMessage());
            }
        }
    }
}
