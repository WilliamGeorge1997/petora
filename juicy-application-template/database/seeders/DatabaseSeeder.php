<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminDatabaseSeeder;
use Modules\Client\Database\Seeders\ClientDatabaseSeeder;
use Modules\Employee\Database\Seeders\EmployeeDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AdminDatabaseSeeder::class);
        // $this->call(BranchDatabaseSeeder::class);
        // $this->call(CategoryDatabaseSeeder::class);
        // $this->call(ProductDatabaseSeeder::class);
//        $this->call(CommonDatabaseSeeder::class);
        // $this->call(OrderDatabaseSeeder::class);
        // $this->call(EmployeeDatabaseSeeder::class);
    }
}
