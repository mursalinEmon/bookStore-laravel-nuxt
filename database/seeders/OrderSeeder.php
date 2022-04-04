<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory(30)->create()->each(function (Order $order){
            OrderItem::factory(random_int(1,4))->create([
                'order_id' => $order->id
            ]);
        });
    }
}
