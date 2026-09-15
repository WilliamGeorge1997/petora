<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Enums\OrderStatus as OrderStatusEnum;

class OrderStatusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (OrderStatusEnum::cases() as $case) {
            $key = Str::snake($case->name);
            
            OrderStatus::create([
                'title' => [
                    'en' => __('order::status.' . $key, [], 'en'),
                    'ar' => __('order::status.' . $key, [], 'ar'),
                ],
            ]);
        }
    }
}
