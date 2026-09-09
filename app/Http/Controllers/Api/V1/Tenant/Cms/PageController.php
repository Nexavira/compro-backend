<?php

namespace App\Http\Controllers\Api\V1\Tenant\Cms;

use App\Http\Controllers\Controller;
use App\Models\CMS\TenantPage;
use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function show(Request $request, $tenant_slug, $slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->first();
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'error'   => 'Tenant tidak ditemukan'
            ], 404);
        }

        $page = TenantPage::where('cms_tenant_pages.tenant_template_id', $tenant->tenantTemplate->id ?? null)
            ->join('cms_tenant_templates', 'cms_tenant_pages.tenant_template_id', '=', 'cms_tenant_templates.id')
            ->join('cms_global_templates', 'cms_global_templates.id', '=', 'cms_tenant_templates.global_template_id')
            ->select('cms_tenant_pages.*', 'cms_global_templates.title as template_title', 'cms_global_templates.slug as template_slug')
            ->where('cms_tenant_pages.is_active', 1)
            ->where('cms_tenant_pages.slug', $slug)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'error'   => 'Halaman tidak ditemukan'
            ], 404);
        }

        $blocks = $page->content_blocks ?? [];
        $sections = [];

        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                if (isset($block['type']) && isset($block['data'])) {
                    $sections[$block['type']] = $block['data'];
                }
            }
        }

        $meta = is_string($page->meta) ? json_decode($page->meta, true) : ($page->meta ?? []);

        $brandSettings = $tenant->settings['brand_settings'] ?? [];

        return response()->json([
            'success' => true,
            'data'    => [
                'title'   => $page->title,
                'slug'    => $page->slug,
                'template_title'   => $page->template_title ?? null,
                'template_slug'    => $page->template_slug ?? null,
                'brand'   => $brandSettings['brand'] ?? null,
                'seo'     => $meta['seo'] ?? null,
                'social'  => $brandSettings['social'] ?? null,
                'blocks'  => [
                    'sections' => $sections
                ],
            ]
        ]);
    }

    public function index(Request $request, $tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->first();
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'error'   => 'Tenant tidak ditemukan'
            ], 404);
        }

        $pages = TenantPage::where('cms_tenant_pages.tenant_template_id', $tenant->tenantTemplate->id ?? null)
            ->join('cms_tenant_templates', 'cms_tenant_pages.tenant_template_id', '=', 'cms_tenant_templates.id')
            ->join('cms_global_templates', 'cms_global_templates.id', '=', 'cms_tenant_templates.global_template_id')
            ->select('cms_tenant_pages.title', 'cms_tenant_pages.slug', 'cms_global_templates.title as template_title', 'cms_global_templates.slug as template_slug', 'cms_tenant_pages.created_at', 'cms_tenant_pages.updated_at')
            ->where('cms_tenant_pages.is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $pages
        ]);
    }
}
