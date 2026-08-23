<?php

use Modules\Branch\Entities\Branch;
use Modules\Package\Entities\Package;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Package::class)->index()->constrained()->restrictOnDelete();
            $table->decimal('price', 10, 2);
            $table->unsignedTinyInteger('duration_months');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('is_active')->default(true);
            $table->timestamps();
        });

        DB::statement("INSERT INTO `permissions` (`name`, `guard_name`, `category`, `display`)
            VALUES
                ('Index-subscription' , 'admin', 'Subscription', 'Index'),
                ('Create-subscription' , 'admin', 'Subscription', 'Create'),
                ('Edit-subscription' , 'admin', 'Subscription', 'Edit'),
                ('Delete-subscription' , 'admin', 'Subscription', 'Delete')
             ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
