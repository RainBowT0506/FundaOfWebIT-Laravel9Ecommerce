<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfCategories = 10;

        DB::table('categories')->truncate(); // Optional: Remove existing records before seeding

        // Using the factory to generate fake data
        \App\Models\Category::factory($numberOfCategories)->create();
    }
}
