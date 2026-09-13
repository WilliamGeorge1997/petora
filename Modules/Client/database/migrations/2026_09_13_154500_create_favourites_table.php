<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Client;
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
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Store::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Clinic::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['client_id', 'product_id', 'store_id']);
            $table->unique(['client_id', 'product_id', 'clinic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
