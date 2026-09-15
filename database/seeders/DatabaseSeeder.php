<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminDatabaseSeeder;
use Modules\Common\Database\Seeders\CommonDatabaseSeeder;
use Modules\Country\Database\Seeders\CountryDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderMethodDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderStatusDatabaseSeeder;
use Modules\Order\Database\Seeders\PaymentMethodDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminDatabaseSeeder::class,
            CommonDatabaseSeeder::class,
            CountryDatabaseSeeder::class,
            OrderStatusDatabaseSeeder::class,
            OrderMethodDatabaseSeeder::class,
            PaymentMethodDatabaseSeeder::class,
        ]);
    }
}
