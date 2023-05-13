<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use App\Models\User;
use App\Models\DriverSalary;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

// use Illuminate\Support\Facades\Schema as FacadesSchema;
// use Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);
        
        // if(Schema::hasTable('driver_salaries')){
        //     $drivers = User::where("role","4")->get();

            
        //     $current = Carbon::now();
        //     foreach($drivers as $driver){

                
        //         if($driver->driver->payment_type =="fixed"){
                    
        //             $salary = DriverSalary::where("driver_id",$driver->id)->whereRaw("MONTH(salary_date) = ?",$current->month)->first();
                    
        //             if(is_null($salary)){
        //                 $new_salary = new DriverSalary;
        //                 $new_salary->driver_id = $driver->id;
        //                 $new_salary->salary_date = $current;
        //                 $new_salary->status = "unpaid";
        //                 $new_salary->salary = $driver->driver->amount;
        //                 $new_salary->save();
                           
        //             }
        //         }
                
        //     }

            
        // }
       
        if(Schema::hasTable('settings')){

            config([
                'app.name' => (Setting::getMeta("company_name")) ? Setting::getMeta("company_name") : "HyperBooking",
                'app.address' => (Setting::getMeta("company_address")) ? Setting::getMeta("company_address") : "United Kingdom",
                'app.contact' => (Setting::getMeta("company_contact")) ? Setting::getMeta("company_contact") : "12345678",
                'app.email' => (Setting::getMeta("company_email")) ? Setting::getMeta("company_email") : "info@hypersoftwares.com",
                'mail.host' => (Setting::getMeta("mail_server_host")) ? Setting::getMeta("mail_server_host") : env('MAIL_HOST'),
                'mail.port' => (Setting::getMeta("mail_server_port")) ? Setting::getMeta("mail_server_port") : env('MAIL_PORT'),
                'mail.username' => (Setting::getMeta("mail_username")) ? Setting::getMeta("mail_username") : env('MAIL_USERNAME'),
                'mail.password' => (Setting::getMeta("mail_password")) ? Setting::getMeta("mail_password") : env('MAIL_PASSWORD'),
                'app.timezone' => (Setting::getMeta("timezone")) ? Setting::getMeta("timezone") : env('TIMEZONE'),
                'app.region' => (Setting::getMeta("region")) ? Setting::getMeta("region") : env('RIGION'),
                'app.logo' => (Setting::getMeta("logo")) ? asset('public/images')."/". Setting::getMeta("logo") : asset('public/dist/img/hyperlogo.png'),
                'app.favicon' => (Setting::getMeta("favicon")) ? asset('public/images')."/". Setting::getMeta("favicon") : asset('public/dist/img/hyperlogo.png'),
                'design.booking.assigndriver' => (Setting::getMeta("assignDriverToBookingDesign")) ? Setting::getMeta("assignDriverToBookingDesign") : 'design1',
                'vat' => (Setting::getMeta("vat")) ? Setting::getMeta("vat") : '0',
                'currency' => (Setting::getMeta("currency")) ? Setting::getMeta("currency") : 'USD',
                'currency-symbol' => (Setting::getMeta("currency")) ? Config('currency-symbols')[Setting::getMeta("currency")] : Config('currency-symbols')['USD']
                
            ]);
        } 
        
        //general settings

        //brain tree configration


        
    }
}
