<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;
use Illuminate\Support\Str;
use Modules\Order\Entities\OrderDetails;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        for ($i=0; $i <10 ; $i++) { 
            $order = Order::create([
                'uuid' => Str::random(6),
                'subtotal' => rand(20,400),
                'tax' => rand(10,20),
                'delivery_fee' => 0,
                'total' => rand(20,400),
                'quantity'=> 4,
                'client_id' => 1,
                'order_status_id' => 7,
                'branch_id'=>1,
                'payment_method_id' => rand(1,2),
                'order_method_id'=>1,
            ]);

            for ($y=0; $y <4 ; $y++) { 
                orderDetails::create([
                    'total' => rand(60,100),
                    'price'=> rand(20,40),
                    'quantity' => rand(1,3),
                    'order_id' => $order->id,
                    'product_id' => rand(1,10),
                ]);
            }
        }
    }
}
