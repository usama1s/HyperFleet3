<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Auth;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    protected $redirectTo = "/";



    public function redirectTo()
    {
        switch (Auth::user()->role) {

            case 1:
            $this->redirectTo = '/dashboard';
            return $this->redirectTo;
                break;
            case 2:
                    $this->redirectTo = '/employee';
                return $this->redirectTo;
                break;
            case 3:
                $this->redirectTo = '/supplier';
                return $this->redirectTo;
                break;
            case 4:
                    $this->redirectTo = '/driver';
                return $this->redirectTo;
                break;
            case 5:
                $this->redirectTo = '/customer';
                return $this->redirectTo;
                break;

            default:
                $this->redirectTo = '/login';
                return $this->redirectTo;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

         $admin = User::create([
            'first_name' => $data['fname'],
            'last_name' => $data['lname'],
            'email' => $data['email'],
            'role' => 1,
            'password' => Hash::make($data['password']),
        ]);
        $admin->assignRole('admin');


        $this->setEnv('INSTALLER','false');

        return $admin;

    }

   public function setEnv($name, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
             $v = (env($name)) ? "true": "false";
             $old = $name . '=' . $v ;
          file_put_contents($path, str_replace(
            $old, $name . '=' . $value, file_get_contents($path)
            ));
        }

    }

}

