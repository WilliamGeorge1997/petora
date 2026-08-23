<?php

use Modules\Branch\Entities\Branch;
use Modules\Coupon\Entities\Coupon;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Entities\OrderMethod;
use Modules\Order\Entities\OrderStatus;
use Illuminate\Database\Schema\Blueprint;
use Modules\Order\Entities\PaymentMethod;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('order_no');
            $table->string('link_code');
            $table->unsignedFloat('subtotal')->default(0);
            $table->unsignedFloat('discount')->default(0);
            $table->tinyInteger('discount_type')->index()->nullable();
            $table->unsignedFloat('tax')->default(0);
            $table->unsignedFloat('delivery_fee')->default(0);
            $table->unsignedFloat('total')->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->string('phone')->nullable();
            $table->string('notes')->nullable();
            $table->foreignIdFor(Branch::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(OrderMethod::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(PaymentMethod::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(OrderStatus::class)->index()->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Coupon::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->string('table_no')->nullable();
            $table->string('car_no')->nullable();
            $table->string('car_color')->nullable();
            $table->string('parking_no')->nullable();
            $table->json('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
