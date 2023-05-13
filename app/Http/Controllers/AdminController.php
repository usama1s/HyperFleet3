<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Auth;
use Intervention\Image\Facades\Image;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AdminController extends Controller
{
    public function profilepage(){
       $user = Auth::user();

        return view('pages.profileupdate',compact('user'));
    }

    public function profileupdate(Request $request){


        $login_user = Auth::user();
        $user  = User::find($login_user->id);

      
        $validations = [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
        ];

        if ($request->email == $login_user->email) {
            $validations['email'] = 'email';
        } else {
            $validations['email'] = 'email|unique:users';
        }

        if (!is_null($request->pre_password)) {

            if(!Hash::check($request->pre_password, $user->password)) {

                return back()->withInput()->withErrors(
                    [
                        'pre_password' => 'Wrong Password',
                    ],
                );

            }
        
        }
       
        if (!is_null($request->pre_password)) {
            [
            $validations['password'] = 'required|confirmed|min:6',
            $validations['pre_password']     = 'required'
            ];
        }
        
        $messages = [
            'password.required' => 'New password field is required.',
            'pre_password.required' => 'Old password field is required.'
        ];

        $this->validate($request, $validations,$messages);
        
        if(!is_null($request->password)){
            $user->password = Hash::make($request->password);
        }
        
        $user->email = $request->email;
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $image = $request->file('image');

         if($user->role==2){

            if (!empty($image)) {  // need to fix this image code below TODO: del old img, rnd fname
                // image upload
                $input['imagename'] = $user->staff->image;                
                $destinationPath = public_path('assets/staff');
                $img = Image::make($image->getRealPath());
                $img->resize(600, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
                $user->staff->image = $input['imagename'];               
                $user->staff->save();
            } 
            else {
                // no image upload
                $user->staff->save();
            }
         }

         if($user->role==3){ 
             if (!empty($image)) {                 
                 $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
                 $destinationPath = public_path('storage/assets/suppliers/');
                 $img = Image::make($image->getRealPath());
                 $img->resize(640, 480, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.$input['imagename']);
                 
                 $img_path = public_path('storage/assets/suppliers/').$user->supplier->image;
                 File::delete($img_path);
                 
                 $user->supplier->image = $input['imagename'];
                 
                 $user->supplier->save();                 
             } else {
                // no image upload
                $user->supplier->save();
            }
         }

        $user->save();
        return back()->with('success', 'Profile Updated');
     }
}
