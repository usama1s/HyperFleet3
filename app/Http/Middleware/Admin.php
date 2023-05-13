<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use User;

class Admin
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }
      
        if (Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3) {
            return $next($request);
        }

        $destinations = [
            1 => 'admin',
            2 => 'employee',
            3 => 'supplier',
            4 => 'driver',
            5 => 'customer',
            6 => 'my-booking-agent',
        ];
        
        if (Auth::user()->role != null) {
            return redirect(route($destinations[Auth::user()->role]));
        } else {
            abort(403, 'UnAuthoirzed');
        }
    }
}
