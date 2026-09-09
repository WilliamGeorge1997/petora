<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Client;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class, 'follower_id')->index()->constrained('clients')->cascadeOnDelete();
            $table->foreignIdFor(Client::class, 'following_id')->index()->constrained('clients')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['follower_id', 'following_id'], 'follows_follower_following_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
