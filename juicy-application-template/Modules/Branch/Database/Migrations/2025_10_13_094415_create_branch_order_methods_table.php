<?php

use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Entities\OrderMethod;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchOrderMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_order_methods', function (Blueprint $table) {
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(OrderMethod::class)->index()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_order_methods');
    }
}
