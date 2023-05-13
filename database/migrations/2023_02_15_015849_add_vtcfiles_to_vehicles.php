<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVtcfilesToVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function ($table) {
            $table->string('vtc_detail')->nullable();
            $table->date('vtc_expiry')->nullable();
            $table->binary('vtc_file')->nullable();

            $table->string('inspection_detail')->nullable();
            $table->date('inspection_expiry')->nullable();
            $table->binary('inspection_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function ($table) {
            $table->dropColumn('vtc_detail');
            $table->dropColumn('vtc_expiry');
            $table->dropColumn('vtc_file');
            $table->dropColumn('inspection_detail');
            $table->dropColumn('inspection_expiry');
            $table->dropColumn('inspection_file');
        });
    }
}
