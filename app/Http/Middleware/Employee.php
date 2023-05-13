<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Employee
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

        if (Auth::user()->role == 2) {
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

        return redirect(route($destinations[Auth::user()->role]));
    }
}
