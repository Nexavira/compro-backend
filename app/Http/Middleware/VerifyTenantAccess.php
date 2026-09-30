<?php

namespace App\Http\Middleware;

use App\Models\Tenant\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant_slug');

        if (!$tenantSlug) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant slug is required.',
                'data'    => null,
            ], 400);
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
                'data'    => null,
            ], 404);
        }

        if ($tenant->is_suspended) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant account has been suspended.',
                'data'    => null,
            ], 403);
        }

        $user = $request->user();

        if (!$user || !$user->canAccessTenant($tenant)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this tenant.',
                'data'    => null,
            ], 403);
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
