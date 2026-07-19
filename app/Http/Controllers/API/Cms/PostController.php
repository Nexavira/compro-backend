<?php

namespace App\Http\Controllers\API\Cms;

use App\Http\Controllers\Controller;
use App\Models\CMS\Post;
use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;

class PostController extends Controller
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

        $post = Post::where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'error'   => 'Post tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'title'    => $post->title,
                'slug'     => $post->slug,
                'excerpt'  => $post->content,
                'body'     => $post->body,
                'metadata' => $post->metadata ?? [],
                'created_at' => $post->created_at,
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

        $posts = Post::where('tenant_id', $tenant->id)
            ->where('is_active', 1)
            ->select('id', 'title', 'slug', 'content', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $posts
        ]);
    }
}
