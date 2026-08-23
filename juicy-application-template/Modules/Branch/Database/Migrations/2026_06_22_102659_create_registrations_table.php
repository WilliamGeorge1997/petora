<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('address');
            $table->string('phone');
            $table->string('image')->nullable();
            $table->string('theme');
            $table->boolean('is_order_enabled')->default(0);
            $table->string('manager_name');
            $table->string('manager_phone');
            $table->text('location_url')->nullable();
            $table->string('working_from')->nullable();
            $table->string('working_to')->nullable();
            $table->boolean('is_working_24_hours')->default(0);
            $table->boolean('is_completed')->default(0);
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
        Schema::dropIfExists('registrations');
    }
}
