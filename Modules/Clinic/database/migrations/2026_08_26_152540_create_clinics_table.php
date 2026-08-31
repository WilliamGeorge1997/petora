<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Country\Models\City;
use Modules\Country\Models\Country;
use Modules\Country\Models\Zone;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(Country::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(City::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Zone::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
