<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();
            $table->string('notes')->nullable();
            $table->foreignIdFor(BookingStatus::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Booking::class)->index()->constrained()->cascadeOnDelete();
            $table->morphs('historible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_histories');
    }
};
