<?php

namespace App\Http\Middleware;

use Closure;

class CheckOrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $businessId = $request->header('BusinessId');
        if($businessId){
            session()->put('business_id', $businessId);
        }

        return $next($request);
    }
}
