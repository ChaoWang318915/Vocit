<?php

namespace App\Http\Middleware;

use Closure;

class SetBusinessSession
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
        if(auth()->check()){

            $user = auth()->user();
            if($user->is_blocked){
                return redirect('/account/suspended');
            }

            if(!session()->get('active_business')){
                $business = auth()->user()->active_business;
                $businessId = '';
                if($business){
                    $businessId = $business->id;
                }
                session()->put('active_business', $businessId);
            }
        }

        return $next($request);
    }
}
