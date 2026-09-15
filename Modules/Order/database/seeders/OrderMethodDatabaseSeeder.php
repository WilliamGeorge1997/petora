<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Order\Models\OrderMethod;
use Modules\Order\Enums\OrderMethod as OrderMethodEnum;

class OrderMethodDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (OrderMethodEnum::cases() as $case) {
            $key = Str::snake($case->name);
            
            OrderMethod::create([
                'title' => [
                    'en' => __('order::order_method.' . $key, [], 'en'),
                    'ar' => __('order::order_method.' . $key, [], 'ar'),
                ],
            ]);
        }
    }
}
