<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\VehicleClass;
use Illuminate\Support\Facades\Route;

use App\Events\MessageEvent;
use App\Http\Controllers\BookingAgentController;
use App\Http\Controllers\BookingController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

Auth::routes();

#################### ADMINISTRATION ROUTES ######################################

Route::get('/', function () {
    if (env('INSTALLER')) {
        return view('auth.register');
    } else {
        return redirect('/dashboard');
    }
});

Route::group(['middleware' => ['admin', 'CheckBlock']], function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('admin');

    Route::get('profile', 'AdminController@profilepage');
    Route::post('profile-update', 'AdminController@profileupdate')->name('admin.profileupdate');

    // Vehicles Routes
    Route::resource('/vehicles', 'VehicleController');
    Route::get('/vehicles/approved_and_disapproved/{id}', 'VehicleController@approved_and_disapproved')->name('vehicles.approved_and_disapproved');
    Route::get("/vehicles/delete/{id}", "VehicleController@destroy");
    Route::post("/vehicles/bulk-delete", "VehicleController@bulkDestroy")->name("vehicles.bulkdestroy");
    Route::get('/vehicles/assign-to-driver/{vehicle_id}', 'VehicleController@assignToDriver');
    Route::get('/vehicles/remove-assign-to-driver/{vehicle_id}', 'VehicleController@removeAssignToDriver');
    Route::post('/vehicles/assign-to-driver', 'VehicleController@assignToDriverSave')->name('vehicles.assigntodriver');
    Route::get('vehicles/go-to-maintenance/{vehicle_id}', 'VehicleController@goToMaintenance');
    Route::get('vehicles/remove-maintenance/{vehicle_id}', 'VehicleController@removeMaintenance');
    Route::get('search/vehicles', 'VehicleController@search')->name('vehicles.search');
    Route::get('category_vehicles', 'VehicleController@selectSupplier')->name('categories.vehicle');

    // Vehicles Classes Routes
    Route::resource('/vehicles-classes', 'VehicleClassController');
    Route::get('/vehicles-classes/delete/{vehicleClass}', 'VehicleClassController@destroy');
    Route::post('/vehicles-classes/bulk-delete', 'VehicleClassController@bulkDestroy')->name('vehicles-classes.bulkdestroy');

    //Pricing schema and routing routes
    Route::resource('/pricings', 'PricingSchemeController');
    Route::get('/pricing/approved_and_disapproved/{id}', 'PricingSchemeController@approved_and_disapproved')->name('pricing.approved_and_disapproved');
    Route::get('/pricing/delete/{id}', 'PricingSchemeController@destroy');
    Route::get('/pricing/routes/{id}', 'PricingSchemeController@getPricingRoutes')->name('pricing.routes');
    Route::get('/pricing/routes/create/{id}', 'PricingSchemeController@createPricingRoute')->name('route.create');
    Route::post('/pricing/routes/store', 'PricingSchemeController@storePricingRoute')->name('route.store');
    Route::get('/pricing/routes/edit/{id}', 'PricingSchemeController@editPricingRoute')->name('route.edit');
    Route::post('/pricing/routes/update', 'PricingSchemeController@updatePricingRoute')->name('route.update');
    Route::get('/pricing/routes/delete/{id}', 'PricingSchemeController@deletePricingRoute')->name('route.delete');
    //end

    // Bookings Routes
    Route::resource('/bookings', 'BookingController');
    Route::get('/today-bookings', 'BookingController@todayBooking')->name('today-bookings');
    Route::get('/bookings/delete/{booking}', 'BookingController@destroy')->name('destroy');
    Route::post('/bookings/bulk-delete', 'BookingController@bulkDestroy')->name('bookings.bulkdestroy');
    Route::get('/bookings/assignDriverToBooking/{booking_id}', 'BookingController@assignDriverToBooking');
    Route::get('/bookings/removeBookingToDriver/{booking_id}', 'BookingController@removeDriverToBooking');
    Route::get('/bookings/assignBookingToSupplier/{booking_id}', 'BookingController@assignBookingToSupplier');
    Route::get('/bookings/removeBookingToSupplier/{booking_id}', 'BookingController@removeBookingToSupplier');
    Route::post('/bookings/assignBookingToSupplier', 'BookingController@assignBookingToSupplierSave')->name('bookings.assignBookingToSupplier');
    Route::post('/bookings/assignDriverToBooking', 'BookingController@assignDriverToBookingSave')->name('bookings.assignDriverToBooking');
    Route::get('search/bookings', 'BookingController@search')->name('bookings.search');


    // market-offers
    Route::get('/market-offers', 'BookingController@marketOffers')->name('market-offers');
    Route::post('/market-offers/capture', 'BookingController@marketOffersCapture')->name('booking.capture');


    //booking invoice
    //Route::resource('/booking-invoices', 'BookingInvoiceController');

    // Shifts Routes
    Route::resource('/shift', 'ShiftController');
    Route::get('/shift/delete/{shift}', 'ShiftController@destroy');
    Route::post('shift/bulk-delete', 'ShiftController@bulkDestroy')->name('shift.bulkdestroy');
    Route::get('shift_category', 'ShiftController@selectSupplier')->name('categories.shift');

    //apikey
    Route::resource('/apikey', 'APIController');

    // Driver Routes
    Route::resource('/drivers', 'DriverController');
    Route::get('/drivers/approved_and_disapproved/{id}', 'DriverController@approved_and_disapproved')->name('drivers.approved_and_disapproved');

    Route::get("/drivers/delete/{id}", "DriverController@destroy");
    Route::post("/drivers/bulk-delete", "DriverController@bulkDestroy")->name("drivers.bulkdestroy");
    Route::get('drivers/assignShift/{user_id}', 'DriverController@assignShift');
    Route::post('/drivers/assignShift', 'DriverController@assignShiftSave')->name('drivers.assignshift');
    Route::get('drivers/assignVehicle/{user_id}', 'DriverController@assignVehicle');
    Route::post('/drivers/assignVehicle', 'DriverController@assignVehicleSave')->name('drivers.assignvehicle');
    Route::get('drivers/remove-assign-vehicle/{user_id}', 'DriverController@removeAssignVehicle');
    Route::get('drivers/remove-assign-shift/{user_id}', 'DriverController@removeAssignShift');
    Route::get('active-drivers', 'DriverController@activedrivers');
    Route::get('search/driver', 'DriverController@search')->name('drivers.search');
    Route::get('category_drivers', 'DriverController@selectSupplier')->name('categories.driver');
    Route::post('driver/block', 'DriverController@blockDriver')->name('driver.block');
    Route::post('driver/unblock', 'DriverController@unblockDriver')->name('driver.unblock');

    //supplier route
    Route::resource('/suppliers', 'SupplierController');
    Route::get('/suppliers/delete/{id}', 'SupplierController@destroy');
    Route::post('/suppliers/bulk-delete', 'SupplierController@bulkDestroy')->name('suppliers.bulkdestroy');
    Route::get('search/suppliers', 'SupplierController@search')->name('suppliers.search');
    Route::post('supplier/block', 'SupplierController@blockSupplier')->name('supplier.block');
    Route::post('supplier/unblock', 'SupplierController@unblockSupplier')->name('supplier.unblock');
    Route::post('supplier/sale-status', 'SupplierController@saleStatus')->name('supplier.sale-status');

    //Booking Agent Routes

    Route::resource('/booking-agent', 'BookingAgentController');
    Route::get('/booking-agent/delete/{id}', 'BookingAgentController@destroy');
    // Route::post('/suppliers/bulk-delete', 'SupplierController@bulkDestroy')->name('suppliers.bulkdestroy');
    // Route::get('search/suppliers', 'SupplierController@search')->name('suppliers.search');
    Route::post('booking-agent/block', 'BookingAgentController@blockBookingAgent')->name('booking-agent.block');
    Route::post('booking-agent/unblock', 'BookingAgentController@unblockBookingAgent')->name('booking-agent.unblock');

    //Permissions
    Route::resource('/permission', 'PermissionController');

    //Staff
    Route::resource('/staff', 'StaffController');
    Route::post('/staff/bulk-delete', 'StaffController@bulkDestroy')->name('staff.bulkdestroy');
    Route::get('/staff/delete/{staff}', 'StaffController@destroy')->name('staff.delete');
    Route::post('staff/block', 'StaffController@blockStaff')->name('staff.block');
    Route::get('staff/unblock/{id}', 'StaffController@unblockStaff')->name('staff.unblock');

    //Roles
    Route::resource('/role', 'RoleController');
    Route::get('/role/delete/{role}', 'RoleController@destroy')->name('role.delete');
    Route::post('role/bulk-delete', 'RoleController@bulkDestroy')->name('role.bulkdestroy');

    //Settings
    Route::get('/settings', 'SettingController@index')->name('settings.index');
    Route::post('/settings/save', 'SettingController@save')->name('settings.save');

    //vouchers routes
    Route::resource('vouchers', 'VoucherController');
    Route::get('/vouchers/delete/{voucher}', 'VoucherController@destroy');
    Route::post('vouchers/bulk-delete', 'VoucherController@bulkDestroy')->name('vouchers.bulkdestroy');

    ########### ACCOUNT's #####################
    // Booking Invoices
    Route::resource('booking-invoice', 'BookingInvoiceController');
    Route::get('/booking-invoice/delete/{invoice}', 'BookingInvoiceController@destroy')->name('booking-invoice.destroy');
    Route::get('/generate-pdf/{invoice}', 'BookingInvoiceController@generatePDF')->name('generate.pdf');
    Route::post('booking-invoice/bulk-delete', 'BookingInvoiceController@bulkDestroy')->name('booking-invoice.bulkdestroy');

    //Payments

    Route::resource('payments', 'PaymentController');
    Route::post('/payments/supplier/receive', 'PaymentController@supplierReceive')->name('payment.supplier.receive');
    Route::post('/payments/supplier/sendpayment', 'PaymentController@supplierSendPayment')->name('payment.supplier.pay');
    Route::get('/payments/delete/{payment}', 'PaymentController@destroy')->name('payments.destroy');
    Route::post('payments/bulk-delete', 'PaymentController@bulkDestroy')->name('payments.bulkdestroy');

    //suppliers payable

    Route::get('account/suppliers', 'AccountController@supplierBooking')->name('account.supplier');
    Route::get('account/suppliers/{id}', 'AccountController@supplierbyIdBooking')->name('account.supplierbyid');

    //Drivers payable

    Route::get('account/drivers', 'AccountController@driversBookings')->name('account.drivers');
    Route::get('account/drivers/{id}', 'AccountController@driverbyIdBooking')->name('account.driverbyid');
    Route::get('account/driver/salary/paid/{salary}', 'AccountController@driverSalaryPaid')->name('account.driver.salary.paid');

    //customer routes
    Route::get('customers', 'CustomerController@index');
    Route::post('customer/block', 'CustomerController@blockCustomer')->name('customer.block');
    Route::get('customer/unblock/{id}', 'CustomerController@unblockCustomer')->name('customer.unblock');
    Route::post('/customer/bulk-delete', 'CustomerController@destroy')->name('customers.destroy');
    Route::get('customer-bookings/{customer}', 'CustomerController@customerBooking')->name('customer.booking');
});

#################### Administrator Routes Ends ######################################

#################### Driver  Routes ######################################

Route::group(['middleware' => ['driver', 'CheckBlock']], function () {
    Route::get('/driver', function () {
        return view('auth/driver.pages.dashboard');
    })->name('driver');

    Route::get('driver/profile', 'DriverController@profilePage')->name('driver.profilePage');
    Route::post('driver/profile', 'DriverController@profileUpdate')->name('driver.profileupdate');
    Route::get('driver/pendingbookings', 'BookingController@pendingbookings')->name('driver.pendings');

    Route::get('driver/startride/{id}', 'BookingController@startride');
    Route::get('driver/acceptedbookings', 'BookingController@acceptedbookings');
    Route::get('driver/pickupclient/{id}', 'BookingController@pickupclient');
    Route::get('driver/finishride/{id}', 'BookingController@finishride');
    Route::get('driver/completedbookings', 'BookingController@completedbookings');
    Route::get('driver/no-show/{id}', 'BookingController@noshow');
    Route::post('/driver-bookingaction', 'BookingController@bookingaction')->name('driver.bookingaction');
    Route::get('driver/canceledbookings', 'BookingController@canceledbookings');
    Route::get('driver/todaybookings', 'BookingController@todaybookings');
    Route::get('driver/vehicle', 'VehicleController@assignedvehicle')->name('driver.vehicle');
});
#################### Driver Routes Ends ######################################



################### Booking Agent Routes ###################################3


Route::group(['prefix' =>'my-booking-agent', 'middleware' => ['booking-agent','CheckBlock']], function () {
    Route::get('/', function () {
        return view('auth.booking-agent.pages.dashboard');
    })->name('my-booking-agent');

   

    Route::get('/profile', 'BookingAgentController@profilePage')->name('my-booking-agent.profilePage');
    Route::post('/profile', 'BookingAgentController@profileUpdate')->name('my-booking-agent.profileupdate');
    
    Route::get('booking',[BookingController::class,'bookingAgentIndex'])->name('my-booking-agent.booking.index');
    Route::get('booking/create',[BookingController::class,'bookingAgentCreateBooking'])->name('my-booking-agent.create-booking');
    Route::post('booking/create',[BookingController::class,'bookingAgentStoreBooking'])->name('my-booking-agent.booking.store');
    


    Route::get('driver/pendingbookings', 'BookingController@pendingbookings')->name('driver.pendings');
    Route::get('driver/startride/{id}', 'BookingController@startride');
    Route::get('driver/acceptedbookings', 'BookingController@acceptedbookings');
    Route::get('driver/pickupclient/{id}', 'BookingController@pickupclient');
    Route::get('driver/finishride/{id}', 'BookingController@finishride');
    Route::get('driver/completedbookings', 'BookingController@completedbookings');
    Route::get('driver/no-show/{id}', 'BookingController@noshow');
    Route::post('/driver-bookingaction', 'BookingController@bookingaction')->name('driver.bookingaction');
    Route::get('driver/canceledbookings', 'BookingController@canceledbookings');
    Route::get('driver/todaybookings', 'BookingController@todaybookings');
    Route::get('driver/vehicle', 'VehicleController@assignedvehicle')->name('driver.vehicle');
});


##############################################################################3
// Messaging
Route::resource('/message', 'MessageController');
Route::get('/message/delete/{message}', 'MessageController@destroy')->name('message.delete');
Route::post('/message/bulkdestroy/', 'MessageController@bulkdestroy')->name('message.bulkdestroy');

Route::get('/employee', function () {
    return view('pages.dashboard');
})
    ->name('employee')
    ->middleware('employee');

Route::get('/supplier', function () {
    return view('pages.dashboard');
})
    ->name('supplier')
    ->middleware('supplier');

#################### Public  Routes ######################################

//api form
Route::get('bookme', 'APIController@apiEmbed')->name('embed');
Route::any('choose-vehicle', 'APIController@chooseVehicle')->name('vehicle.choose');
Route::any('customer-info', 'APIController@customerInfo')->name('customer.info');
Route::any('vehiclebooking', 'APIController@vehiclebookingform')->name('vehiclebookingform');
Route::any('customer-details', 'APIController@customerDetails')->name('customer_details');
Route::post('confirm-booking', 'APIController@bookingSave')->name('confirm.booking');

//customer routes
 Route::get('signup','CustomerController@create');
 Route::post('signup','CustomerController@store')->name("customer.store");

Route::group(['middleware' => ['auth:customer' ,'CheckBlock']], function () {

    Route::get('/me','CustomerController@trips')->name('customer');

    Route::get('me/trips','CustomerController@trips');
    Route::get('me/profile','CustomerController@edit');
    Route::post('me/profile','CustomerController@update')->name("customer.profileupdate");

 });

// route for processing payment
Route::post('paypal', 'PaymentController@payWithpaypal');

// route for check status of the payment
Route::get('payment-status', 'PaymentController@getPaymentStatus')->name('payment-status');

Route::get('payment-cancel', function () {
    return Redirect::back()->with('error', 'Booking Cancelled');
})->name('payment-cancel');

####################### Public Routes End Here #############################

#################### Other  Routes ######################################

Route::post('notifications-mark-as-read', function () {
    $user = Auth::user();
    $user->unreadNotifications->markAsRead();
    return 'true';
});

#################### Other Routes  Ends ######################################

Route::get('payment-cancel', function () {
    return Redirect::back()->with('error', 'Booking Cancelled');
})->name('payment-cancel');

Route::get('/test', function () {
    // $username = "";///Your Username
    // $password = "";///Your Password
    // $mobile = "923356380184";///Recepient Mobile Number
    // $sender = "muzammmil.com";
    // $message = "Test SMS From sendpk.com";

    // ////sending sms

    // $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."";
    // $url = "https://sendpk.com/api/sms.php?username=$username&password=$password";
    // $ch = curl_init();
    // $timeout = 30; // set to zero for no timeout
    // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    // curl_setopt($ch, CURLOPT_URL,$url);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    // $result = curl_exec($ch);
    // /*Print Responce*/
    // echo $result;
});
