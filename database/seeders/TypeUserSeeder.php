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
        foreach($datas['restaurants'] as $key => $restaurant){
            $restaurant_model = User::all()[$key];
            foreach($restaurant['type_list'] as $type){
                $type_id = Type::where('name', '=', $type)->get()->pluck('id');
                $restaurant_model->types()->attach($type_id);
            }
        }
    }
}
