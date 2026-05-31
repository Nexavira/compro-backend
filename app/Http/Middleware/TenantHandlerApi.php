<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\Tenant\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantHandlerApi
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $tenant = Tenant::resolveFromRequest($host);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found.'], 404);
        }

        if ($tenant->is_suspended) {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        // if header includes optional api key, check api key
        $token = $request->header('X-Tenant-API-Key');
        if ($token != null) {
            $active_token = $tenant->getActiveApiKey;

            if ($token !== $active_token->token) {
                return response()->json(['error' => 'API Key is invalid or has been blocked.'], 401);
            }

            $active_token->update(['last_used_at' => now()]);
            $request->merge(['current_tenant_id' => $active_token->tenant_id]);
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
