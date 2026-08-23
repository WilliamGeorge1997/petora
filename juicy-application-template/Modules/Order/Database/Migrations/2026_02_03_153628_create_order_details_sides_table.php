<?php

use Modules\Product\Entities\Side;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Entities\SideValue;
use Modules\Order\Entities\OrderDetails;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsSidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details_sides', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderDetails::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Side::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(SideValue::class)->index()->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('order_details_sides');
    }
}
