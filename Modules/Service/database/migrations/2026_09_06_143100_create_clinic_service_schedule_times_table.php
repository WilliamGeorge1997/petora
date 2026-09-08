<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Service\Models\ClinicServiceSchedule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_service_schedule_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ClinicServiceSchedule::class)->index()->constrained()->cascadeOnDelete();
            $table->time('from');
            $table->time('to');
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_service_schedule_times');
    }
};
