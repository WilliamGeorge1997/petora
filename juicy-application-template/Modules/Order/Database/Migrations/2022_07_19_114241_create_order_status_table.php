<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Order\Entities\OrderStatus;

class CreateOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->timestamps();
        });

        $OrderStatus = [
            ['en' => 'Sent To Branch', 'ar' => 'تم الارسال للفرع'],
            ['en' => 'Preparing', 'ar' => 'جاري التحضير'],
            ['en' => 'Ready', 'ar' => 'جاهز للتسليم'],
            ['en' => 'In Delivery', 'ar' => 'قيد التوصيل'],
            ['en' => 'Done', 'ar' => 'تم الاستلام'],
            ['en' => 'Fail', 'ar' => 'فشل في التوصيل'],
            ['en' => 'Cancelled', 'ar' => 'تم الالغاء'],
        ];

        foreach ($OrderStatus as $data) {
            OrderStatus::create(['title' => $data]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_status');
    }
}
