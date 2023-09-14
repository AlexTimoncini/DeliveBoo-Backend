<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = base_path('database/data');
        $json = File::get($dataPath.'/type_list.json');
        $data = json_decode($json, true);
        foreach($data['types'] as $type){
            $newType = new Type();
            $newType->name = $type['name'];
            $newType->logo = $type['logo'];
            $newType->save();
        }

    }
}
