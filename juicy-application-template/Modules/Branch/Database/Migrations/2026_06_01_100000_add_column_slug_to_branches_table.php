<?php

use Illuminate\Support\Str;
use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSlugToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable()->unique();
        });

        Branch::query()->orderBy('id')->each(function (Branch $branch) {
            $branch->update(['slug' => $this->uniqueSlug($branch->getTranslation('title', 'en'))]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function uniqueSlug(string $titleEn): string
    {
        $base = Str::slug($titleEn);
        $slug = $base;
        $i = 1;

        while (Branch::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
