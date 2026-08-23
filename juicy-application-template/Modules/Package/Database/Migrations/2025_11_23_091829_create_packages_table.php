<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedDecimal('price', 10, 2)->default(0);
            $table->unsignedDecimal('discounted_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('duration_months')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_special')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        DB::statement("INSERT INTO `permissions` (`name`, `guard_name`, `category`, `display`)
            VALUES
                ('Index-package' , 'admin', 'Package', 'Index'),
                ('Create-package' , 'admin', 'Package', 'Create'),
                ('Edit-package' , 'admin', 'Package', 'Edit'),
                ('Delete-package' , 'admin', 'Package', 'Delete')
             ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
