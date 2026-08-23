<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Branch\Entities\Branch;

class CreateBranchSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency_ar')->nullable();
            $table->string('currency_en')->nullable();
            $table->string('tax')->nullable();
            $table->string('about_ar')->nullable();
            $table->string('about_en')->nullable();
            $table->string('terms_ar')->nullable();
            $table->string('terms_en')->nullable();
            $table->string('app_background_image')->nullable();
            $table->string('app_offer_image')->nullable();
            $table->string('app_primary_color');
            $table->string('app_secondary_color');
            $table->string('app_indicator_color');
            $table->string('app_text_color');
            $table->string('tax_number')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('x')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
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
        Schema::dropIfExists('branch_settings');
    }
}
