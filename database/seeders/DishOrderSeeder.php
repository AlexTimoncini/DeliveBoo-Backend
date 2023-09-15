<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishOrder;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DishOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allOrders = [];
        $json = File::get('database/data/restaurant_list.json');
        $datas = json_decode($json, true);
        foreach ($datas['restaurants'] as $restaurant) {
            foreach ($restaurant['order_list'] as $order) {
                array_push($allOrders, $order);
            }
        }
        foreach ($allOrders as $key => $order) {
            foreach ($order['dish_list'] as $dish) {
                $dish_id = Dish::where('name', '=', $dish)->get()->pluck('id')[0];
                $order_model = Order::all()[$key];
                $order_model->dishes()->attach($dish_id, ['customer_address' => $order['customer_address']]);
            }
        }
    }
}
