<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
                $newOrder->interior = 'ciao';
                $newOrder->doorbell = 'gino';
                $newOrder->phone = '0000000000';
                $newOrder->successful = 1;
                $newOrder->total_price = 100.00;
                $newOrder->save();
            }
        }
    }
}
