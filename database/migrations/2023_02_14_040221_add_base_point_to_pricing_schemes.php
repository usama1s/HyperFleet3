<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBasePointToPricingSchemes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_schemes', function($table) {
            $table->text('base_point')->nullable();
            $table->string('start_radius')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_schemes', function($table) {
            $table->dropColumn('base_point');
            $table->dropColumn('start_radius');
        });
    }
}
