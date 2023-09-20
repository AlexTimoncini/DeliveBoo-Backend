<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/restaurant_list.json');

        $datas = json_decode($json, true);
        foreach ($datas['restaurants'] as $data) {
            $newRestaurant = new User();
            $newRestaurant->name = $data['name'];
            $newRestaurant->email = $data['email'];
            $newRestaurant->password = $data['password'];
            $newRestaurant->vat_number = $data['vat_number'];
            $newRestaurant->phone = $data['phone'];
            $newRestaurant->description = $data['description'];
            $newRestaurant->address = $data['address'];
            $newRestaurant->image = $data['image'];
            Storage::put('/logos'.$data['logo'], file_get_contents("database/data/restaurants_logos".$data['logo']));
            $newRestaurant->logo = 'logos'.$data['logo'];
            $newRestaurant->closer_time = '21:00';
            $newRestaurant->open_time = '8:00';
            $newRestaurant->save();
        }
    }
}
