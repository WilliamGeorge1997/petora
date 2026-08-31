<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminDatabaseSeeder;
use Modules\Common\Database\Seeders\CommonDatabaseSeeder;
use Modules\Country\Database\Seeders\CountryDatabaseSeeder;

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
        ]);
    }
}
