<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Branch\Entities\Branch;

class CreateBranchOrderDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedFloat('min_total');
            $table->enum('type', ['percent', 'fixed']);
            $table->unsignedFloat('value');
            $table->timestamps();
        });

        Schema::table('branch_settings', function (Blueprint $table) {
            $table->boolean('is_order_discount_enabled')->default(0)->after('is_checkout_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_order_discounts');
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->dropColumn('is_order_discount_enabled');
        });
    }
}
