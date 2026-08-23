<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('phone')->nullable();
            $table->json('address')->nullable();
            $table->string('image')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('qr_code')->unique();
            $table->enum('delivery_fee_method', ['fixed', 'per_km', 'per_charge'])->default('fixed');
            $table->unsignedFloat('delivery_fee_fixed')->default(0);
            $table->unsignedFloat('delivery_fee_per_km')->default(0);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('branches');
    }
}
