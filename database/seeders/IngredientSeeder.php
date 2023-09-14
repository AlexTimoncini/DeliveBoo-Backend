<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = base_path('database/data');
        $json = File::get($dataPath.'/ingredient_list.json');
        $data = json_decode($json, true);
        foreach($data['ingredients'] as $ingredient){
            $newIngredient = new Ingredient();
            $newIngredient->name = $ingredient['name'];
            $newIngredient->save();
        } 
    }
}
