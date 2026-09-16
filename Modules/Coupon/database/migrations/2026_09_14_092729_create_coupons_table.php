<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedInteger('num_of_uses');
            $table->unsignedInteger('counter')->default(0);
            $table->enum('type',['fixed', 'percent']);
            $table->unsignedInteger('value');
            $table->unsignedInteger('limit')->default(0);
            $table->unsignedInteger('client_uses')->default(1);
            $table->enum('discount_on', ['subtotal', 'delivery', 'both'])->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
