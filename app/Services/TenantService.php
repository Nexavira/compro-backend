<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
use App\Models\CMS\Page;
// use App\Models\Cms\GlobalTemplatePage;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TenantService
{
    /**
     * Clone global template pages to tenant pages
     */
    public static function cloneTemplatesForTenant(Tenant $tenant)
    {
        if (!$tenant->global_template_id) {
            return;
        }

        // Only clone if the tenant doesn't have pages yet to avoid duplicates
        if (Page::where('tenant_id', $tenant->id)->exists()) {
            return;
        }

        // note: global template page sudah tidak ada

        // $globalPages = GlobalTemplatePage::where('global_template_id', $tenant->global_template_id)->get();

        // foreach ($globalPages as $globalPage) {
        //     Page::create([
        //         'tenant_id' => $tenant->id,
        //         'title' => $globalPage->title,
        //         'slug' => $globalPage->slug,
        //         'content_blocks' => $globalPage->content_blocks,
        //         'meta' => $globalPage->meta,
        //         'is_active' => 1,
        //         'version' => 0,
        //     ]);
        // }
    }

    /**
     * Send password reset link to user
     */
    public static function sendPasswordResetLink(User $user)
    {
        try {
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);
            Log::info("Password reset link sent successfully to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send password reset link to {$user->email}: " . $e->getMessage());
        }
    }
}
