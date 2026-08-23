<?php

use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchQrSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchQrSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_qr_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->unique()->constrained()->cascadeOnDelete();

            $table->string('dot_type')->default('square');
            $table->string('dot_color')->default('#000000');
            $table->string('dot_color_type')->default('single');
            $table->string('dot_color_2')->nullable();
            $table->string('dot_gradient_type')->default('linear');
            $table->unsignedSmallInteger('dot_gradient_rotation')->default(0);

            $table->string('bg_color')->default('#ffffff');
            $table->string('bg_color_type')->default('single');
            $table->string('bg_color_2')->nullable();
            $table->string('bg_gradient_type')->default('linear');
            $table->unsignedSmallInteger('bg_gradient_rotation')->default(0);

            $table->string('corner_square_type')->default('square');
            $table->string('corner_square_color')->default('#000000');
            $table->string('corner_square_color_type')->default('single');
            $table->string('corner_square_color_2')->nullable();
            $table->string('corner_square_gradient_type')->default('linear');
            $table->unsignedSmallInteger('corner_square_gradient_rotation')->default(0);

            $table->string('corner_dot_type')->default('square');
            $table->string('corner_dot_color')->default('#000000');
            $table->string('corner_dot_color_type')->default('single');
            $table->string('corner_dot_color_2')->nullable();
            $table->string('corner_dot_gradient_type')->default('linear');
            $table->unsignedSmallInteger('corner_dot_gradient_rotation')->default(0);

            $table->unsignedSmallInteger('width')->default(300);
            $table->unsignedSmallInteger('height')->default(300);
            $table->unsignedSmallInteger('margin')->default(0);

            $table->string('qr_image')->nullable();
            $table->boolean('is_qr_image_enabled')->default(0);
            $table->boolean('hide_background_dots')->default(1);
            $table->decimal('image_size', 3, 2)->default(0.40);
            $table->unsignedTinyInteger('image_margin')->default(3);
            $table->boolean('use_branch_img')->default(0);

            $table->unsignedTinyInteger('type_number')->default(0);
            $table->string('mode')->default('Byte');
            $table->string('error_correction_level')->default('H');

            $table->timestamps();
        });

        Branch::with('settings')->orderBy('id')->each(function (Branch $branch) {
            $rawQrImage = $branch->settings?->getRawOriginal('qr_image');
            $enabled = (bool) $branch->settings?->is_qr_image_enabled;

            BranchQrSetting::create([
                'branch_id' => $branch->id,
                'qr_image' => $rawQrImage,
                'is_qr_image_enabled' => $enabled,
                'use_branch_img' => $enabled && $rawQrImage,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_qr_settings');
    }
}
