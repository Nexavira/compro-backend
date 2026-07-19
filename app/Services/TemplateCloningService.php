<?php

namespace App\Services;

use App\Models\CMS\Page;
use App\Models\GlobalTemplate;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateCloningService
{

    public function cloneTemplateToTenant(GlobalTemplate $template, Tenant $tenant)
    {
        DB::beginTransaction();
        try {

            $currentSettings = $tenant->settings ?? [];

            $currentSettings['brand_settings'] = $template->brand_settings;

            $tenant->settings = $currentSettings;
            $tenant->saveQuietly(); 

            $templatePages = $template->pages;

            foreach ($templatePages as $templatePage) {

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
