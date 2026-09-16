<?php

namespace Modules\Booking\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Booking\Enums\BookingStatus as BookingStatusEnum;
use Modules\Booking\Models\BookingStatus;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (BookingStatusEnum::cases() as $case) {
            $key = Str::snake($case->name);

            BookingStatus::updateOrCreate(
                ['id' => $case->value],
                [
                    'title' => [
                        'en' => __('booking::status.' . $key, [], 'en'),
                        'ar' => __('booking::status.' . $key, [], 'ar'),
                    ],
                    'is_active' => true,
                ]
            );
        }
    }
}
