<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Client;
use Modules\Pet\Models\PetType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('breed')->nullable();
            $table->enum('gender', ['m', 'f']);
            $table->date('date_of_birth')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PetType::class)->index()->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
