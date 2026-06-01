<?php

namespace App\Services;

use App\Models\Cms\Page;
use App\Models\GlobalTemplate;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateCloningService
{
    /**
     * Clone a Global Template (brand settings and pages) to a Tenant.
     *
     * @param GlobalTemplate $template
     * @param Tenant $tenant
     * @return void
     */
    public function cloneTemplateToTenant(GlobalTemplate $template, Tenant $tenant)
    {
        DB::beginTransaction();
        try {
            // 1. Clone brand settings to tenant's settings
            $currentSettings = $tenant->settings ?? [];
            
            // Merge existing settings with template's brand settings
            // If template has brand_settings, we inject it into a 'brand' key or merge it
            $currentSettings['brand_settings'] = $template->brand_settings;
            
            $tenant->settings = $currentSettings;
            $tenant->saveQuietly(); // Use saveQuietly to prevent infinite loops if called from Observer

            // 2. Clone Pages
            $templatePages = $template->pages;

            foreach ($templatePages as $templatePage) {
                // Check if tenant already has a page with this slug to prevent duplicates
                $existingPage = Page::where('tenant_id', $tenant->id)
                    ->where('slug', $templatePage->slug)
                    ->first();

                if (!$existingPage) {
                    Page::create([
                        'tenant_id' => $tenant->id,
                        'title' => $templatePage->title,
                        'slug' => $templatePage->slug,
                        'content_blocks' => $templatePage->content_blocks,
                        'meta' => $templatePage->meta,
                        'is_active' => 1,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to clone template to tenant: ' . $e->getMessage());
            throw $e;
        }
    }
}
