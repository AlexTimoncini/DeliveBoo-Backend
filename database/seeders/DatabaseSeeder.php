<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TypeSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            IngredientSeeder::class,
            DishSeeder::class,
            OrderSeeder::class,
            TypeUserSeeder::class,
            DishOrderSeeder::class,
            DishIngredientSeeder::class
        ]);
    }
}
