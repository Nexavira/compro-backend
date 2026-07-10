<?php

namespace App\Http\Controllers\API\Cms;

use App\Http\Controllers\Controller;
use App\Models\GlobalTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Get a single global template by its slug.
     * Note: Global Templates might be accessible to all tenants or specific ones.
     * Here we just filter by is_active.
     */
    public function show(Request $request, $slug)
    {
        $template = GlobalTemplate::with('pages')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (!$template) {
            return response()->json([
                'success' => false,
                'error'   => 'Template tidak ditemukan'
            ], 404);
        }

        // For templates, we can just grab the blocks of the first page (like 'home') for preview purposes
        $firstPage = $template->pages->first();
        $blocks = $firstPage ? $firstPage->content_blocks : [];
        $sections = [];

        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                if (isset($block['type']) && isset($block['data'])) {
                    $sections[$block['type']] = $block['data'];
                }
            }
        }

        $meta = $firstPage && is_string($firstPage->meta) ? json_decode($firstPage->meta, true) : ($firstPage->meta ?? []);
        $brandSettings = is_string($template->brand_settings) ? json_decode($template->brand_settings, true) : ($template->brand_settings ?? []);

        return response()->json([
            'success' => true,
            'data'    => [
                'title'       => $template->title,
                'slug'        => $template->slug,
                'description' => $template->description,
                'brand'       => $brandSettings['brand'] ?? null,
                'seo'         => $meta['seo'] ?? null,
                'social'      => $brandSettings['social'] ?? null,
                'category'    => [
                    'name' => $template->tenantCategory->name,
                    'code' => $template->tenantCategory->code,
                    'description' => $template->tenantCategory->description ?? null,
                ],
                'blocks'      => [
                    'sections' => $sections
                ],
            ]
        ]);
    }

    /**
     * Get all active global templates.
     */
    public function index(Request $request)
    {
        $templates = GlobalTemplate::where('is_active', 1)
            ->select('id', 'tenant_category_id', 'title', 'slug', 'description')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $templates
        ]);
    }
}
