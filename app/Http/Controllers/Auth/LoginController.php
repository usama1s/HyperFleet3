<?php

namespace App\Http\Controllers\Auth;

use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 2; // Default is 1

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = "/dashboard";

    public function redirectTo()
    {
        // switch(Auth::user()->roles){
        //     case 1:
        //         $this->redirectTo = '/dashboard';
        //         return $this->redirectTo;
        //         break;
        //     case 2:
        //         $this->redirectTo = '/employee';
        //         return $this->redirectTo;
        //         break;
        //     case 3:
        //         $this->redirectTo = '/supplier';
        //         return $this->redirectTo;
        //         break;
        //     case 4:
        //             $this->redirectTo = '/driver';
        //         return $this->redirectTo;
        //         break;
        //     case 5:
        //         $this->redirectTo = '/customer';
        //         return $this->redirectTo;
        //         break;
          
        //     default:
        //         $this->redirectTo = '/login';
        //         return $this->redirectTo;
        // }

        if(Auth::user()){
            
          

            $ref = session()->get("login_ref_url");
            session()->forget('login_ref_url');
            
            if(!is_null($ref)){

                $this->redirectTo = $ref;
            
            }else{
                $this->redirectTo = '/dashboard';
            }

            
            return $this->redirectTo;
        }else{
            $this->redirectTo = '/login';
            return $this->redirectTo;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
