<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBranchSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('theme')->default(1);
            $table->boolean('is_order_enabled')->default(1);
            $table->string('wifi_username')->nullable();
            $table->string('wifi_password')->nullable();
            $table->string('header_image')->nullable();
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

        });
    }
}
