<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Clinic\Models\Clinic;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Store::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Clinic::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'store_id']);
            $table->unique(['product_id', 'clinic_id']);
        });

        DB::statement('
            ALTER TABLE product_sellers
            ADD CONSTRAINT seller_exclusive_check
            CHECK (
                (store_id IS NOT NULL AND clinic_id IS NULL)
                OR
                (store_id IS NULL AND clinic_id IS NOT NULL)
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sellers');
        DB::statement('
            ALTER TABLE product_sellers
            DROP CONSTRAINT check_seller_exclusive
        ');
    }
};
