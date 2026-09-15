<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Enums\PaymentMethod as PaymentMethodEnum;

class PaymentMethodDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PaymentMethodEnum::cases() as $case) {
            $key = Str::snake($case->name);
            
            PaymentMethod::create([
                'title' => [
                    'en' => __('order::payment_method.' . $key, [], 'en'),
                    'ar' => __('order::payment_method.' . $key, [], 'ar'),
                ],
            ]);
        }
    }
}
