<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Client;
use Modules\Clinic\Models\Clinic;
use Modules\Product\Models\Product;
use Modules\Product\Models\SellerProduct;
use Modules\Store\Models\Store;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Store::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Clinic::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SellerProduct::class, 'seller_product_id')->index()->constrained('seller_product')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['client_id', 'seller_product_id']);
            $table->unique(['client_id', 'product_id', 'store_id']);
            $table->unique(['client_id', 'product_id', 'clinic_id']);
        });

        DB::statement(
            'ALTER TABLE favourites ADD CONSTRAINT favourites_seller_exclusive_check
            CHECK (
            (store_id IS NOT NULL AND clinic_id IS NULL)
             OR
             (store_id IS NULL AND clinic_id IS NOT NULL))'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
