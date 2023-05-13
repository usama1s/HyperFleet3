<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBookingAgents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');     
            $table->string('company_name');
            $table->string('agreement')->nullable();
            $table->string('credit')->nullable();               
            $table->string('address');
            $table->string('contact_person');
            $table->string('payment_method');
            // $table->string('bank_detail_id');   
            $table->string('payment_terms');   
            $table->binary('profile_image')->nullable();                
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
        Schema::dropIfExists('table_booking_agents');
    }
}
