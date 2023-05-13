<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Vehicle;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('license_plate')->unique();

            $table->bigInteger('vehicle_class_id');

            $table->string('manufacturer');
            $table->string('car_model');
            $table->string('car_year');
            $table->string('car_color');

            $table->string('insurance_detail');
            $table->date('insurance_expiry');
            $table->binary('insurance_file');

            $table->string('registration_detail');
            $table->date('registration_expiry');
            $table->binary('registration_file');

            $table->integer('seats');
            $table->integer('luggage');


            $table->string('price');
            $table->string('description');
            $table->binary('image');
            $table->string('status')->nullable();

            $table->string('driver_id')->nullable();
            $table->integer('supplier_id')->nullable();

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
        Schema::dropIfExists('vehicles');
    }
}
