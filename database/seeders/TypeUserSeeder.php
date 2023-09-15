<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/restaurant_list.json');
        $datas = json_decode($json, true);
        foreach($datas['restaurants'] as $restaurant){
            $restaurant_id = User::where('name', '=', $restaurant['name'])->get()->pluck('id')[0];
            $restaurant_model = User::all()[$restaurant_id - 1];
            foreach($restaurant['type_list'] as $type){
                $type_id = Type::where('name', '=', $type)->get()->pluck('id');
                $restaurant_model->types()->attach($type_id);
            }
        }
    }
}
