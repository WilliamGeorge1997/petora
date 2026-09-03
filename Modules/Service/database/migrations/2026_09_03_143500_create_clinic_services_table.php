<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Clinic\Models\Clinic;
use Modules\Service\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clinic::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->index()->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('duration');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['clinic_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_services');
    }
};
