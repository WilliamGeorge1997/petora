<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\Models\Client;
use Modules\Community\Models\Comment;
use Modules\Community\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Post::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Comment::class, 'parent_id')->nullable()->index()->constrained('comments')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
