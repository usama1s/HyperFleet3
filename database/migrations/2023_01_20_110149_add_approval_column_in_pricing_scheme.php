<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalColumnInPricingScheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_schemes', function (Blueprint $table) {
            $table->string('admin_approval')->default('0')->after('supplier_id')->comment('0 pending and 1 approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_schemes', function (Blueprint $table) {
            $table->dropColumn('admin_approval');
        });
    }
}
