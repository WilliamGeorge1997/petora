<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Entities\OrderMethod;

class CreateOrderMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_methods', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        $OrderMethod = [
            ['en' => 'Receipt From Branch', 'ar' => 'استلام من الفرع'],
            ['en' => 'Receipt In Car', 'ar' => 'استلام في السيارة'],
            ['en' => 'Receipt In Home', 'ar' => 'استلام في المنزل'],
        ];
        foreach ($OrderMethod as $data) {
            OrderMethod::create(['title' => $data]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_methods');
    }
}
