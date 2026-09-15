<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Address;
use Modules\Client\Models\Client;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Models\ClinicDeliveryScheduleTime;
use Modules\Coupon\Models\Coupon;
use Modules\Driver\Models\Driver;
use Modules\Order\Models\OrderMethod;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Models\PaymentMethod;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreDeliveryScheduleTime;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->decimal('subtotal', 10, 2)->unsigned()->default(0);
            $table->decimal('discount', 10, 2)->unsigned()->default(0);
            $table->tinyInteger('discount_type')->index()->nullable();
            $table->decimal('tax', 10, 2)->unsigned()->default(0);
            $table->decimal('delivery_fee', 10, 2)->unsigned()->default(0);
            $table->decimal('total', 10, 2)->unsigned()->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->date('delivery_date')->nullable();
            $table->time('delivery_time_from')->nullable();
            $table->time('delivery_time_to')->nullable();
            $table->foreignIdFor(Client::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Store::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(StoreDeliveryScheduleTime::class)->index()->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Clinic::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ClinicDeliveryScheduleTime::class)->index()->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Address::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Coupon::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Driver::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(OrderMethod::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(PaymentMethod::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(OrderStatus::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        DB::statement('
            ALTER TABLE orders ADD CONSTRAINT orders_seller_and_schedule_check CHECK (
                (
                    store_id IS NOT NULL 
                    AND clinic_id IS NULL 
                    AND clinic_delivery_schedule_time_id IS NULL
                ) 
                OR 
                (
                    store_id IS NULL 
                    AND clinic_id IS NOT NULL 
                    AND store_delivery_schedule_time_id IS NULL
                )
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
