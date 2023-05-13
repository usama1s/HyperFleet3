<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Str;

class CheckBlock
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
         if(!Auth::user()){
            return redirect()->route('login')->withMessage("You are unauthorized to access this page. Please login First.");
         }

         $user =Auth::user();


        if(is_null($user->block_until)){
            
            if(!is_null($user->block_type)){
                auth()->logout();
                $message = "Your account has been suspended. Please contact administrator.";
                return redirect()->route('login')->withMessage($message);
            }
        }
        else{
            if(!is_null($user->block_type)){
                $banned_days = now()->diffInDays($user->block_until);
                auth()->logout();
                $message = 'Your account has been suspended for '.$banned_days.' '.Str::plural('day', $banned_days).'. Please contact administrator.';
                return redirect()->route('login')->withMessage($message);
            }
            
        }


        return $next($request);
    }
}
