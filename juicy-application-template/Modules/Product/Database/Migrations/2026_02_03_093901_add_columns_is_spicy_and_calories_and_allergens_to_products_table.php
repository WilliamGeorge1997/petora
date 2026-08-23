<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIsSpicyAndCaloriesAndAllergensToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('calories')->after('sort_order')->nullable();
            $table->json('allergens')->after('calories')->nullable();
            $table->boolean('is_spicy')->after('allergens')->default(false);
            $table->boolean('is_vegetarian')->after('is_spicy')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('calories');
            $table->dropColumn('allergens');
            $table->dropColumn('is_spicy');
            $table->dropColumn('is_vegetarian');
        });
    }
}
