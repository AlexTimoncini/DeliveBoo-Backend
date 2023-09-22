<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Faker\Generator as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $dataPath = base_path('database/data');
        $json = File::get($dataPath . '/restaurant_list.json');
        $data = json_decode($json, true);
        foreach ($data['restaurants'] as $key => $restaurant) {
            $restaurant_id = User::where('name', '=', $restaurant['name'])->get()->pluck('id')[0];
            foreach ($restaurant['order_list'] as $order) {
                $newOrder = new Order();
                $newOrder->user_id = $restaurant_id;
                $newOrder->customer_address = $order['customer_address'];
                $newOrder->first_name = $faker->firstName();
                $newOrder->last_name = $faker->lastName();
                $newOrder->interior = $faker->buildingNumber();
                $newOrder->doorbell = $newOrder->first_name . ' ' . $newOrder->last_name;
                $newOrder->phone = $faker->phoneNumber();
                $newOrder->successful = $faker->boolean();
                $totalPrice = 0;
                foreach ($order['dish_list'] as $dish ) {
                    $dishPrice = Dish::where('name', $dish)->get()->pluck('price')[0];
                    $totalPrice += $dishPrice;
                }
                $newOrder->total_price = $totalPrice;
                $newOrder->save();
            }
        }
    }
}