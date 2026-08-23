<?php

use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAppOfferProductIdAndAppOfferEndsAtToBranchSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->foreignIdFor(Product::class, 'app_offer_product_id')->after('app_offer_image')->nullable()->index()->constrained('products')->nullOnDelete();
            $table->dateTime('app_offer_ends_at')->after('app_offer_product_id')->nullable();
            $table->boolean('app_offer_is_active')->after('app_offer_ends_at')->default(false);
            $table->boolean('send_orders_to_whatsapp')->after('app_offer_is_active')->default(false);
            $table->text('google_rate_url')->after('send_orders_to_whatsapp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_settings', function (Blueprint $table) {
            $table->dropForeign(['app_offer_product_id']);
            $table->dropColumn('app_offer_product_id');
            $table->dropColumn('app_offer_ends_at');
            $table->dropColumn('app_offer_is_active');
            $table->dropColumn('send_orders_to_whatsapp');
            $table->dropColumn('google_rate_url');
        });
    }
}
