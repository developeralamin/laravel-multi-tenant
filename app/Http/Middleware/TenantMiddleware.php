<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Central\Tenant;
use App\Services\TenantDatabaseManager;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle($request, \Closure $next)
    {
        $tenantKey = $request->header('X-TENANT');

        $tenant = Tenant::where('domain', $tenantKey)->firstOrFail();

        TenantDatabaseManager::connect($tenant);

        return $next($request);
    }
}
