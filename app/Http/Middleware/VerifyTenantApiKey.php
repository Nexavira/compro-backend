<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Tenant-API-Key');

        if (!$token) {
            return response()->json(['error' => 'API Key not found in header.'], 401);
        }

        $apiKey = ApiKey::where('token', $token)->where('is_active', 1)->first();

        if (!$apiKey) {
            return response()->json(['error' => 'API Key is invalid or has been blocked.'], 401);
        }

        $apiKey->update(['last_used_at' => now()]);

        $request->merge(['current_tenant_id' => $apiKey->tenant_id]);

        return $next($request);
    }
}
