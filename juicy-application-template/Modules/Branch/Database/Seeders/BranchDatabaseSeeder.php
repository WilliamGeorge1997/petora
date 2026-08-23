<?php

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;

class BranchDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $branch = Branch::create([
            'title' => ['en'=>'elma3mora','ar'=>'فرع المعموره'],
            'phone' => '0501234567',
            'address' => 'testing branch'
        ]);
        $branch->deliveryCharges()->create(['distance' => 10,'price'=>5]);
        // $this->call("OthersTableSeeder");
    }
}
