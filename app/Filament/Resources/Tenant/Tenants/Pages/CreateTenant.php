<?php

namespace App\Filament\Resources\Tenant\Tenants\Pages;

use App\Filament\Resources\Tenant\Tenants\TenantResource;
use App\Models\System\File;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\User;
use App\Models\Auth\DetailUser;
use App\Models\Auth\RoleUser;
use App\Models\Auth\Role;
use App\Models\Transaction\Subscription;
use App\Models\Master\Package;
use App\Models\Transaction\Payment;
use Illuminate\Support\Str;
use App\Services\TemplateCloningService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeTenantMail;
use Illuminate\Support\Facades\Log;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['logo_upload']) && filled($data['logo_upload'])) {
            $path = is_array($data['logo_upload']) ? reset($data['logo_upload']) : $data['logo_upload'];

            $disk = 'public';
            $fileExists = Storage::disk($disk)->exists($path);

            $logoFile = File::create([
                'file_path'      => $path,
                'file_name'      => basename($path),
                'original_name'  => basename($path),
                'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                'storage_disk'   => $disk,
                'is_public'      => 1,
                'is_used'        => 1,
            ]);

            $data['logo_id'] = $logoFile->id;
        }

        if (isset($data['favicon_upload']) && filled($data['favicon_upload'])) {
            $path = is_array($data['favicon_upload']) ? reset($data['favicon_upload']) : $data['favicon_upload'];

            $disk = 'public';
            $fileExists = Storage::disk($disk)->exists($path);

            $faviconFile = File::create([
                'file_path'      => $path,
                'file_name'      => basename($path),
                'original_name'  => basename($path),
                'file_extension' => pathinfo($path, PATHINFO_EXTENSION),
                'mime_type'      => $fileExists ? Storage::disk($disk)->mimeType($path) : 'image/jpeg',
                'file_size'      => $fileExists ? Storage::disk($disk)->size($path) : 0,
                'storage_disk'   => $disk,
                'is_public'      => 1,
                'is_used'        => 1,
            ]);

            $data['favicon_id'] = $faviconFile->id;
        }

        unset($data['logo_upload'], $data['favicon_upload']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $tenantData = collect($data)->except(['user_name', 'user_email', 'user_phone', 'user_password', 'user_password_confirmation', 'subscription_plan'])->toArray();

            $package = null;
            if (!empty($data['subscription_plan'])) {
                $package = Package::find($data['subscription_plan']);
            }

            // Tenant is active if trial_days > 0, otherwise suspended until paid
            $tenantData['is_suspended'] = ($package && $package->trial_days > 0) ? 0 : 1;

            $tenant = static::getModel()::create($tenantData);

            $adminUser = null;
            if (isset($data['user_email'])) {
                // Create random pass
                $pass = Str::random(16);

                // Create Admin User
                $user = User::create([
                    'email' => $data['user_email'],
                    'password' => $pass,
                    'is_active' => 1,
                    'version' => 0,
                ]);

                // Create DetailUser
                DetailUser::create([
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'full_name' => $data['user_name'] ?? 'Admin ' . $tenant->name,
                    'phone_number' => $data['user_phone'] ?? '0',
                ]);

                // Assign Role
                $role = Role::where('code', 'tenant_admin')->first();
                if ($role) {
                    RoleUser::create([
                        'user_id' => $user->id,
                        'role_id' => $role->id,
                    ]);
                }

                $adminUser = $user;
            }

            // Create Subscription and Payment
            if ($package) {
                $status = $package->trial_days > 0 ? 'trial' : 'pending';
                $trialEndAt = $package->trial_days > 0 ? now()->addDays($package->trial_days) : null;

                $subscription = Subscription::create([
                    'tenant_id' => $tenant->id,
                    'package_id' => $package->id,
                    'subscription_number' => 'SUB-' . strtoupper(uniqid()),
                    'package_name' => $package->name,
                    'billing_cycle' => $package->billing_cycle,
                    'status' => $status,
                    'next_billing_date' => $package->billing_cycle === 'annually' ? now()->addYear() : now()->addMonth(),
                    'trial_end_at' => $trialEndAt,
                    'amount' => $package->price,
                ]);

                $dueDate = $package->trial_days > 0 ? now()->addDays($package->trial_days) : now()->addDays(3);

                // Generate Invoice Number: INV-{SEQUENCE}/{TENANT_CODE}/{MM}/{YYYY}
                $paymentCount = Payment::where('tenant_id', $tenant->id)->count();
                $sequenceStr = str_pad($paymentCount + 1, 3, '0', STR_PAD_LEFT);
                $monthStr = now()->format('m');
                $yearStr = now()->format('Y');
                $invoiceNumber = "INV-{$sequenceStr}/{$tenant->code}/{$monthStr}/{$yearStr}";

                Payment::create([
                    'tenant_id' => $tenant->id,
                    'subscription_id' => $subscription->id,
                    'invoice_number' => $invoiceNumber,
                    'description' => 'Pembayaran Langganan ' . $package->name,
                    'amount_due' => $package->price,
                    'due_date' => $dueDate,
                    'status' => 'unpaid',
                    'is_active' => 1,
                    'version' => 0,
                ]);
                // If trial, clone templates and send welcome email immediately
                if ($package->trial_days > 0) {
                    if ($tenant->global_template_id && $tenant->globalTemplate) {
                        $cloningService = new TemplateCloningService();
                        $cloningService->cloneTemplateToTenant($tenant->globalTemplate, $tenant);
                    }

                    if ($adminUser) {
                        $token = Password::createToken($adminUser);
                        $resetUrl = url('/admin/password-reset/' . $token . '?email=' . urlencode($adminUser->email));
                        $recipientEmail = app()->environment('production') ? $adminUser->email : 'nexavira26@gmail.com';
                        try {
                            Mail::to($recipientEmail)->send(new WelcomeTenantMail($tenant, $adminUser, $resetUrl));
                        } catch (\Exception $e) {
                            Log::error('Failed to send welcome email for trial: ' . $e->getMessage());
                        }
                    }
                }
            }

            return $tenant;
        });
    }

    protected function afterCreate(): void
    {
        $tenant = $this->record;

        if ($tenant->logo_id) {
            File::where('id', $tenant->logo_id)->update([
                'tenant_id'    => $tenant->id,
                'related_id'   => $tenant->id,
                'related_type' => get_class($tenant),
            ]);
        }

        if ($tenant->favicon_id) {
            File::where('id', $tenant->favicon_id)->update([
                'tenant_id'    => $tenant->id,
                'related_id'   => $tenant->id,
                'related_type' => get_class($tenant),
            ]);
        }
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin') => __('Beranda'),
            static::getResource()::getNavigationGroup(),
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            'Tambah',
        ];
    }
}
