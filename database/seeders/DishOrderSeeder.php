<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishOrder;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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
            $dishes_quantity = array_count_values($order['dish_list']);
            $dishes = [];
            foreach($order['dish_list'] as $dish){
                if(!in_array($dish, $dishes)){
                    array_push($dishes, $dish);
                }
            };
            $order_model = Order::all()[$key];
            foreach ($dishes as  $dish) {
                $dish_id = Dish::where('name', '=', $dish)->get()->pluck('id');
                $order_model->dishes()->attach($dish_id, ['quantity' => $dishes_quantity[$dish]]);
            }
        }           
    }
}

