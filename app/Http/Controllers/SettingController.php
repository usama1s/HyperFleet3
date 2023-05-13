<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    

    public function index(){
        return view("pages.settings.index");
    }

    public function timezonelist($zone){
       return $timezones = config('timezones.'.$zone);
    }

    public function save(Request $request){
      

            $validation_array = [
            
                'vat' => 'numeric',
                'company_email' =>'email'
        
            ];
            $validator = Validator::make($request->all(), $validation_array);

            if ($validator->fails()) { // on validator found any error
             return back()->withErrors($validator)->withInput();
         }
 
     

       // company information
       $company_name = $request->company_name;
       $company_address = $request->company_address;
       $company_contact = $request->company_contact;
       $company_email = $request->company_email;
       $logo = $request->file('image');
       $favicon = $request->file('favicon');

        //vat and currency
        $vat = $request->vat;
        $currency = $request->currency;



       //Mail Server
       $mail_server_host = $request->mail_server_host;
       $mail_server_port = $request->mail_server_port;
       $mail_username = $request->mail_username;
       $mail_password = $request->mail_password;

       //Time Setting

       $region = $request->region;
       $timezone = $request->timezone;

       
       $setting = Setting::whereMeta("company_name")->first();
       $setting->value =  $company_name;
       $setting->save();

       $setting = Setting::whereMeta("company_address")->first();
       $setting->value =  $company_address;
       $setting->save();

       $setting = Setting::whereMeta("company_contact")->first();
       $setting->value =  $company_contact;
       $setting->save();

       $setting = Setting::whereMeta("company_email")->first();
       $setting->value =  $company_email;
       $setting->save();

       $setting = Setting::whereMeta("vat")->first();
       $setting->value = $vat;
       $setting->save();

       $setting = Setting::whereMeta("currency")->first();
       $setting->value = $currency;
       $setting->save();

       $setting = Setting::whereMeta("mail_server_host")->first();
       $setting->value =  $mail_server_host;
       $setting->save();

       $setting = Setting::whereMeta("mail_server_port")->first();
       $setting->value =  $mail_server_port;
       $setting->save();

       $setting = Setting::whereMeta("mail_username")->first();
       $setting->value =  $mail_username;
       $setting->save();

       $setting = Setting::whereMeta("mail_password")->first();
       $setting->value =  $mail_password;
       $setting->save();

       $setting = Setting::whereMeta("region")->first();
       $setting->value =  $region;
       $setting->save();


       $setting = Setting::whereMeta("timezone")->first();
       $setting->value =  $timezone;
       $setting->save();


       if (!empty($logo)) {
        $setting = Setting::whereMeta("logo")->first();
        $logo = 'logo.'.$logo->getClientOriginalExtension();
        $logo_path = public_path('assets/images/');
        request()->image->move($logo_path, $logo);

        $setting->value = $logo;
        $setting->save();
        }

        if (!empty($favicon)) {
            $setting = Setting::whereMeta("favicon")->first();
            $favicon = 'favicon.'.$favicon->getClientOriginalExtension();
            $favicon_path = public_path('images/');
            request()->favicon->move($favicon_path, $favicon);
    
            $setting->value = $favicon;
            $setting->save();
            }
       
       return back()->with("success","settings updated");
    }

    public function assignDriverToBookingDesign(Request $request){
        $design = $request->design;

        $setting = Setting::whereMeta("assignDriverToBookingDesign")->first();
        $setting->value =  $design;
        $setting->save();
        return "true";
        
    }
}
