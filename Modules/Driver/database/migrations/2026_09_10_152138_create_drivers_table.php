<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Clinic\Models\Clinic;
use Modules\Country\Models\City;
use Modules\Country\Models\Country;
use Modules\Country\Models\Zone;
use Modules\Store\Models\Store;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('license_id')->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(Store::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Clinic::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Country::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(City::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Zone::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('fcm_token')->nullable();
            $table->string('locale')->default('en');
            $table->boolean('allow_notification')->default(true);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
