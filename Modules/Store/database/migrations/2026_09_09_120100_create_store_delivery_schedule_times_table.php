<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Store\Models\StoreDeliverySchedule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_delivery_schedule_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoreDeliverySchedule::class)->index()->constrained()->cascadeOnDelete();
            $table->time('from');
            $table->time('to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_delivery_schedule_times');
    }
};
