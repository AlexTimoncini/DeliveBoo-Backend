<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $dataPath = base_path('database/data');
            $json = File::get($dataPath.'/category_list.json');
            $data = json_decode($json, true);
            foreach($data['types'] as $type){
                $newType = new Category();
                $newType->name = $type['name'];
                $newType->color = $type['color'];
                $newType->save();
            }   
        }
    }
}
