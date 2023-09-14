<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $dataPath = base_path('database/data');
        $json = File::get($dataPath . '/restaurant_list.json');
        $data = json_decode($json, true);



        foreach ($data['restaurants'] as $restaurant) {
            foreach ($restaurant['dish_list'] as $dish) {
                $newDish = new Dish;
                $newDish->name = $dish['name'];
                $newDish->category_id = Category::where('name', '=', $dish['category'])->get()->pluck('id')[0];
                $newDish->description = $dish['description'];
                $newDish->price = $dish['price'];
                $newDish->photo = $dish['photo'];
                $newDish->available = $dish['availability'];
                $newDish->visible = 1;
                $newDish->save();
            }
        }
        //
    }
};
