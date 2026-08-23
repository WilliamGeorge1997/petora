<?php

use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sides', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('max_selection');
            $table->boolean('is_active');
            $table->timestamps();
        });
        
        //Permissions
        $permissions = [
            ['name' => 'Index-side', 'guard_name' => 'admin', 'category' => 'Side', 'display' => 'Index'],
            ['name' => 'Create-side', 'guard_name' => 'admin', 'category' => 'Side', 'display' => 'Create'],
            ['name' => 'Edit-side', 'guard_name' => 'admin', 'category' => 'Side', 'display' => 'Edit'],
            ['name' => 'Delete-side', 'guard_name' => 'admin', 'category' => 'Side', 'display' => 'Delete'],
        ];
        DB::table('permissions')->insert($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sides');
    }
}
