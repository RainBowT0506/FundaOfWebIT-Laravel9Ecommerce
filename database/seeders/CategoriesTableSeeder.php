<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfCategories = 10;

        // Remove the foreign key constraint
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    
        // Truncate the table
        DB::table('categories')->truncate();
    
        // Restore the foreign key constraint
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
    
        // Using the factory to generate fake data
        \App\Models\Category::factory($numberOfCategories)->create();
    }
}
