<?php

use Modules\Product\Entities\Side;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSideValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_values', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->foreignIdFor(Side::class)->index()->constrained()->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_values');
    }
}
