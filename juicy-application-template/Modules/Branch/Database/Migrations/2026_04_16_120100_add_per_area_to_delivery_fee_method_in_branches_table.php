<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPerAreaToDeliveryFeeMethodInBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `branches` MODIFY `delivery_fee_method` ENUM('fixed','per_km','per_charge','per_area') NOT NULL DEFAULT 'fixed'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `branches` MODIFY `delivery_fee_method` ENUM('fixed','per_km','per_charge') NOT NULL DEFAULT 'fixed'");
    }
}
