<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_schemes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('supplier_id')->nullable();
            $table->string('title')->nullable();
            $table->string('up_to_ten')->nullable();
            $table->string('ten_to_hundred')->nullable();
            $table->string('hundred_to_twoHundred')->nullable();
            $table->string('twoHundred_and_above')->nullable();
            $table->string('price_per_hour')->nullable();
            $table->string('price_per_day')->nullable();
            $table->string('minimum_hours')->nullable();
            $table->string('pickup_fee_per_pickup')->nullable();
            $table->string('waiting_time_per_min')->nullable();
            $table->string('airport_pickup_fee')->nullable();
            $table->string('job_discount')->nullable();
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
        Schema::dropIfExists('pricing_schemes');
    }
}
