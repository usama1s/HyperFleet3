<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
           
            $ref = session()->get("login_ref_url");
            session()->forget('login_ref_url');
            
            if(!is_null($ref)){

                $redirectTo = $ref;
            
            }else{
                $redirectTo = '/dashboard';
            }
            return redirect($redirectTo);
        }

        return $next($request);
    }
}
