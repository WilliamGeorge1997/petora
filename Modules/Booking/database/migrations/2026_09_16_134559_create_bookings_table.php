<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Booking\Models\BookingStatus;
use Modules\Client\Models\Client;
use Modules\Clinic\Models\Clinic;
use Modules\Coupon\Models\Coupon;
use Modules\Order\Models\PaymentMethod;
use Modules\Pet\Models\Pet;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceScheduleTime;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no');
            $table->decimal('subtotal', 10, 2)->unsigned()->default(0);
            $table->decimal('discount', 10, 2)->unsigned()->default(0);
            $table->unsignedTinyInteger('discount_type')->index()->nullable();
            $table->decimal('tax', 10, 2)->unsigned()->default(0);
            $table->decimal('total', 10, 2)->unsigned()->default(0);
            $table->date('booking_date');
            $table->foreignIdFor(Client::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Pet::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Clinic::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ClinicService::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ClinicServiceScheduleTime::class)->index()->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Coupon::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(PaymentMethod::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(BookingStatus::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['clinic_service_schedule_time_id', 'booking_date', 'booking_status_id'], 'bookings_slot_date_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
