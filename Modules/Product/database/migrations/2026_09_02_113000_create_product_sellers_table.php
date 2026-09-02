<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_sellers', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->morphs('sellerable');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite primary key
            $table->primary(['product_id', 'sellerable_id', 'sellerable_type'], 'product_sellers_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sellers');
    }
};
