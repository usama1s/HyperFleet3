<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('type');
            $table->unsignedBigInteger('driver_id')->nullable();              
            $table->string('status')->default('open');
            $table->string('pickup_point');
            $table->string('drop_off')->nullable();
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('duration')->nullable();
            $table->integer('no_of_adults')->nullable();
            $table->integer('no_of_bags')->nullable();
            $table->string('voucher_code')->nullable();
            $table->float('price');
            $table->float('grand_price');
            $table->integer('v_class');
            $table->string('service_type')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('cost_center')->nullable();
            $table->string('contact_no');
            $table->string('pickup_sign')->nullable();
            $table->string('special_instructions')->nullable();
            $table->string('payment_method');
            $table->integer('supplier_id')->nullable();
            $table->integer('vehicle_id')->nullable(); 
            $table->integer('customer_id')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
