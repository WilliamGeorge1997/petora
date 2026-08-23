<?php

use Modules\Product\Entities\Addon;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Entities\OrderDetails;
use Modules\Product\Entities\AddonValue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Branch\Entities\BranchProductAddonValue;

class CreateOrderDetailsAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('price', 10, 2);
            $table->foreignIdFor(OrderDetails::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Addon::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(AddonValue::class)->index()->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('order_details_addons');
    }
}
