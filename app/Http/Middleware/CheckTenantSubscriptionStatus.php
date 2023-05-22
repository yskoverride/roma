<?php

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Tenancy;

class CheckTenantSubscriptionStatus
{
    
    protected $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($this->tenancy->initialized) {
            $tenant = $this->tenancy->tenant;
            
            if (! $tenant['subscription_status']) {
                abort(404);
            }
        }

        return $next($request);
    }
}
