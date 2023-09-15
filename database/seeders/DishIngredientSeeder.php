<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DishIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/restaurant_list.json');
        $datas = json_decode($json, true);
        foreach ($datas['restaurants'] as  $restaurant) {
            foreach ($restaurant['dish_list'] as $dish) {
                $dish_id = Dish::where('name', '=', $dish['name'])->get()->pluck('id');
                $dish_model = Dish::all()[$dish_id[0] - 1];
                foreach($dish['ingredient_list'] as $ingredient){
                    $ingredient_id = Ingredient::where('name', '=', $ingredient)->get()->pluck('id');
                    $dish_model->ingredients()->attach($ingredient_id);
                }
            }
        }
    }
}
