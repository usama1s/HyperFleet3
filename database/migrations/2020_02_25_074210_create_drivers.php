<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedBigInteger('user_id');               
            $table->string('address');
            $table->date('rta_card_expiry');
            $table->date('license_expiry');
            $table->date('emirates_expiry'); 
            $table->string('status')->nullable(); 
            $table->binary('driver_image');
            $table->binary('license_image')->nullable();
            $table->binary('rta_card_image')->nullable();
            $table->binary('emirates_id')->nullable();
            $table->integer('vehicle_id')->nullable();  
            $table->integer('supplier_id')->nullable();
            $table->integer('shift_id')->nullable();      
            $table->unsignedBigInteger('bank_detail_id')->nullable();
            $table->string('payment_type')->nullable(); 
            $table->string('amount')->nullable(); 
            $table->string('credit')->default(0); 
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
        Schema::dropIfExists('drivers');
    }
}
