<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Clinic\Models\Clinic;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clinic::class)->index()->constrained()->cascadeOnDelete();
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->boolean('is_open_24_hours')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_working_hours');
    }
};
