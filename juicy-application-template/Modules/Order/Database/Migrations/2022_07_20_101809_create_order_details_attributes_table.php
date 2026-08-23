<?php

use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Entities\OrderDetails;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeValue;

class CreateOrderDetailsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('price');
            $table->foreignIdFor(OrderDetails::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ProductAttribute::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(ProductAttributeValue::class)->index()->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('order_details_attributes');
    }
}
