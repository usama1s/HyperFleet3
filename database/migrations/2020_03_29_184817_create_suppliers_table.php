<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');     
            $table->string('company_name');
            $table->string('agreement')->nullable();
            $table->string('credit')->nullable();
            $table->string('address');
            $table->string('sales_person');
            $table->string('payment_method');
            $table->string('commission'); 
            $table->string('details')->nullable(); 
            $table->string('bank_details')->nullable();   
            $table->string('payment_terms');   
            $table->binary('image');                  
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
        Schema::dropIfExists('suppliers');
    }
}
