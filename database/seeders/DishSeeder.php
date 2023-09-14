<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Type;
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
        $json = File::get($dataPath . '/dish_list.json');
        $data = json_decode($json, true);

        foreach ($data['dishes'] as $dish) {
            $newDish = new Dish;
            $newDish->name = $dish['name'];
            $newDish->description = $dish['description'];
            $newDish->price = $dish['price'];
            $newDish->photo = $dish['photo'];
            $newDish->available = $dish['availability'];
            $newDish->visible = $dish['visible'];
            $newDish->save();
        }
        //
    }
}
;