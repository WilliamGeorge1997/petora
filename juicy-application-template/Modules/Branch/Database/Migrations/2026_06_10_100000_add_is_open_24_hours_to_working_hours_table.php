<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIsOpen24HoursToWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('working_hours', function (Blueprint $table) {
            $table->boolean('is_open_24_hours')->default(false)->after('day');
        });

        DB::statement('ALTER TABLE `working_hours` MODIFY `from` TIME NULL');
        DB::statement('ALTER TABLE `working_hours` MODIFY `to` TIME NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `working_hours` MODIFY `from` TIME NOT NULL');
        DB::statement('ALTER TABLE `working_hours` MODIFY `to` TIME NOT NULL');

        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropColumn('is_open_24_hours');
        });
    }
}
