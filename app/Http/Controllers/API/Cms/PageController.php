<?php

namespace App\Http\Controllers\API\Cms;

use App\Http\Controllers\Controller;
use App\Models\CMS\Page;
use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Get a single page by its slug for the current tenant.
     */
    public function show(Request $request, $tenant_slug, $slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->first();
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'error'   => 'Tenant tidak ditemukan'
            ], 404);
        }

        $page = Page::where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->where('is_active', 1)
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
                'brand'   => $brandSettings['brand'] ?? null,
                'seo'     => $meta['seo'] ?? null,
                'social'  => $brandSettings['social'] ?? null,
                'blocks'  => [
                    'sections' => $sections
                ],
            ]
        ]);
    }

    /**
     * Get all active pages for the current tenant.
     */
    public function index(Request $request, $tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->first();
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'error'   => 'Tenant tidak ditemukan'
            ], 404);
        }

        $pages = Page::where('tenant_id', $tenant->id)
            ->join('tnt_tenants', 'cms_pages.tenant_id', '=', 'tnt_tenants.id')
            ->select('cms_pages.title', 'cms_pages.slug', 'tnt_tenants.theme_code', 'cms_pages.created_at', 'cms_pages.updated_at')
            ->where('cms_pages.is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $pages
        ]);
    }
}
