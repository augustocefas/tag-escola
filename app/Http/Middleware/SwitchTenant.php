<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;
use Symfony\Component\HttpFoundation\Response;

class SwitchTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $tenantId = $request->header('X-Tenant-ID')
            ?? $request->header('X-tenant-id')
            ?? $request->query('tenant')
            ?? $request->query('tenant_id');
        if ($tenantId) {
            try {
                $tenant = \App\Models\Tenant::findOrFail($tenantId);
                tenancy()->initialize($tenant);
                return $next($request);
            } catch (\Exception $e) {
                throw TenantCouldNotBeIdentifiedById::make($tenantId);
            } finally {
                tenancy()->end();
            }
        }
        return $next($request);
    }



}
