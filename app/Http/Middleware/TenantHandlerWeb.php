<?php

namespace App\Http\Middleware;

use App\Models\Tenant\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantHandlerWeb
{

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if ($host === 'nexavira.test') {
            return $next($request);
        }

        $tenant = Tenant::resolveFromRequest($host);

        if (!$tenant) {
            abort(404, "Company profile not found.");
        }

        if ($tenant->is_suspended) {
            abort(403, "This account has been suspended.");
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
