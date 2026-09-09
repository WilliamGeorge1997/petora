<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Clinic\Models\ClinicDeliverySchedule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_delivery_schedule_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ClinicDeliverySchedule::class)->index()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('clinic_delivery_schedule_times');
    }
};
