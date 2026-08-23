<?php

use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Product\Entities\ProductType;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('total');
            $table->unsignedFloat('price');
            $table->unsignedInteger('quantity');
            $table->string('note')->nullable();
            $table->foreignIdFor(Order::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ProductType::class)->nullable()->index()->constrained()->restrictOnDelete();
            $table->unsignedFloat('product_price')->default(0);
            $table->unsignedFloat('product_type_price')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
