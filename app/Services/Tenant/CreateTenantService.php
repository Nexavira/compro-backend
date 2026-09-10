<?php

namespace App\Services\Tenant;

use App\Models\Cms\GlobalTemplate;
use App\Models\Master\Package;
use App\Models\System\File;
use App\Models\Tenant\TenantCategory;
use App\Models\Transaction\Subscription;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class CreateTenantService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);

        $tenantService = app('StoreTenantService')->execute($dto, true);
        if (isset($tenantService['error'])) {
            $this->results = $tenantService;
            return;
        }
        $tenant = $tenantService['data'];

        $tenantTemplateService = app('StoreTenantTemplateService')->execute([
            'tenant_id' => $tenant->id,
            'global_template_id' => $dto['global_template_id'] ?? null,
            'is_active' => 1,
            'template_settings' => null,
        ], true);
        if (isset($tenantTemplateService['error'])) {
            $this->results = $tenantTemplateService;
            return;
        }
        $tenantTemplate = $tenantTemplateService['data'];

        $tenantUserService = app('AddTenantUserService')->execute([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->user()->id,
            'role_detail' => $dto['role_detail'] ?? null,
        ], true);
        if (isset($tenantUserService['error'])) {
            $this->results = $tenantUserService;
            return;
        }

        $package = Package::find($dto['package_id']);
        $isTrial = filter_var($dto['is_trial'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $subStatus = 'unpaid';
        $subTrialEndAt = null;
        $subStartAt = now();
        $subEndAt = null;
        $subNextBillingDate = now();
        $subAmount = $package->price;

        if ($isTrial && $package->trial_days > 0) {
            $hasUsedTrial = Subscription::where('tenant_id', $tenant->id)
                ->whereHas('package', function ($q) use ($package) {
                    $q->where('tier', $package->tier);
                })
                ->whereNotNull('trial_end_at')
                ->exists();

            if ($hasUsedTrial) {
                $this->results['error'] = true;
                $this->results['message'] = "Tenant ini sudah pernah menggunakan trial untuk tier {$package->tier}.";
                $this->results['response_code'] = 403;
                return;
            }

            $subStatus = 'trial';
            $subTrialEndAt = now()->addDays($package->trial_days);
            $subNextBillingDate = $subTrialEndAt;
            $subAmount = 0;
        } elseif ($package->price == 0 && $package->setup_fee == 0) {
            $subStatus = 'active';
            $subEndAt = $package->billing_cycle === 'annually' ? now()->addYear() : now()->addMonth();
            $subNextBillingDate = $subEndAt;
            $subAmount = 0;
        } else {
            $subEndAt = $package->billing_cycle === 'annually' ? now()->addYear() : now()->addMonth();
            $subNextBillingDate = $subEndAt;
        }

        $subscriptionService = app('StoreSubscriptionService')->execute([
            'tenant_id' => $tenant->id,
            'package_id' => $package->id,
            'status' => $subStatus,
            'amount' => $subAmount,
            'start_at' => $subStartAt,
            'end_at' => $subEndAt,
            'trial_end_at' => $subTrialEndAt,
            'next_billing_date' => $subNextBillingDate,
        ], true);
        if (isset($subscriptionService['error'])) {
            $this->results = $subscriptionService;
            return;
        }
        $subscription = $subscriptionService['data'];

        $amountDue = ($subStatus === 'trial') ? 0 : ($package->price + $package->setup_fee);

        $paymentStatus = 'unpaid';
        if ($amountDue == 0) {
            $paymentStatus = 'paid';
        }

        $paymentDueDate = now()->addDays(7);
        $paymentDate = null;
        $amountPaid = 0;
        $paymentMethod = null;

        if ($paymentStatus === 'paid') {
            $paymentDate = now();
            $amountPaid = 0;
            $paymentMethod = 'system';
        }

        $paymentService = app('StorePaymentService')->execute([
            'tenant_id' => $tenant->id,
            'subscription_id' => $subscription->id,
            'amount_due' => $amountDue,
            'status' => $paymentStatus,
            'due_date' => $paymentDueDate,
            'payment_date' => $paymentDate,
            'amount_paid' => $amountPaid,
            'payment_method' => $paymentMethod,
        ], true);
        if (isset($paymentService['error'])) {
            $this->results = $paymentService;
            return;
        }
        $payment = $paymentService['data'];

        $this->results['data'] = [
            'tenant' => $tenant,
            'tenant_template' => $tenantTemplate,
            'subscription' => $subscription,
            'payment' => $payment
        ];
        $this->results['message'] = "Tenant successfully created with subscription";
    }

    public function prepare($dto)
    {
        if (isset($dto['logo_uuid']) and $dto['logo_uuid'] != '') {
            $dto['logo_id'] = $this->findIdByUuid(File::query(), $dto['logo_uuid']);
        }
        if (isset($dto['favicon_uuid']) and $dto['favicon_uuid'] != '') {
            $dto['favicon_id'] = $this->findIdByUuid(File::query(), $dto['favicon_uuid']);
        }
        if (isset($dto['tenant_category_uuid']) and $dto['tenant_category_uuid'] != '') {
            $dto['tenant_category_id'] = $this->findIdByUuid(TenantCategory::query(), $dto['tenant_category_uuid']);
        }

        if (isset($dto['package_uuid']) and $dto['package_uuid'] != '') {
            $dto['package_id'] = $this->findIdByUuid(Package::query(), $dto['package_uuid']);
        }

        if (isset($dto['global_template_uuid']) and $dto['global_template_uuid'] != '') {
            $dto['global_template_id'] = $this->findIdByUuid(GlobalTemplate::query(), $dto['global_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'package_uuid' => ['required', 'uuid', new ExistsUuid(new Package())],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate)],
            'tenant_category_uuid' => ['required', 'uuid', new ExistsUuid(new TenantCategory())],
            'logo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'favicon_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'slug' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'slug')],
            'custom_domain' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
            'is_suspended' => ['required', 'integer', 'in:0,1'],
            'role_detail' => ['nullable', 'string', 'max:255'],
            'is_trial' => ['required', 'boolean'],
        ];
    }
}
