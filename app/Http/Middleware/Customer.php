<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Customer
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

        if (Auth::user()->role == 1) {
            return redirect()->route('admin');
        }

        if (Auth::user()->role == 2) {
            return redirect()->route('employee');
        }

        if (Auth::user()->role == 3) {
            return redirect()->route('supplier');
        }

        if (Auth::user()->role == 4) {
            return redirect()->route('driver');
        }
       

        if (Auth::user()->role == 5) {
            //  return redirect()->route('customer');
            return $next($request);
        }

        if (Auth::user()->role == 6) {
            return redirect()->route('my-booking-agent');
        }

        
    }
}
