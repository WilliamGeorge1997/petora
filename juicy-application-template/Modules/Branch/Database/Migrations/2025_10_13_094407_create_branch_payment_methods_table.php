<?php

use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Order\Entities\PaymentMethod;
use Illuminate\Database\Migrations\Migration;

class CreateBranchPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_payment_methods', function (Blueprint $table) {
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PaymentMethod::class)->index()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_payment_methods');
    }
}
