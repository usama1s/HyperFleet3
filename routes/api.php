<?php

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('drivers', 'DriverController@showAllJSON');
Route::post('bookings/{booking?}', 'API\BookingController@showBookingJSON')->name('booking.data');

Route::any('/assignBooking', 'API\BookingController@assignBooking');

Route::get('vehicles', 'VehicleController@showAllJSON');
//vehicles class
Route::get('vehicles_classes', 'VehicleClassController@showAllJSON');

Route::post('voucher-validate','VoucherController@validateVoucher')->name('voucher.validate');

Route::post('change_permission', 'PermissionController@changePermission');
Route::post('get_permission', 'PermissionController@getAllPermissions');
Route::get('timezone-list/{zone}', 'SettingController@timezonelist');
Route::post('/bookings/assign-driver/design','SettingController@assignDriverToBookingDesign');
Route::post('get-customer/{id}','API\CustomerController@show');

Route::post('change-booking-status','API\BookingController@changeBookingStatus')->name('change.status');

Route::post('check-pricing/{pricing_scheme_id}','API\BookingController@checkPricing')->name('booking.checkPricing');
