<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsCallWaiterEnabledToBranchSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->boolean('is_call_waiter_enabled')->after('is_checkout_enabled')->default(0);
        });
    }
        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->dropColumn('is_call_waiter_enabled');
        });
    }
}
