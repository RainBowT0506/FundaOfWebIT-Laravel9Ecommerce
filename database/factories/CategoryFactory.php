<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence,
            'meta_keyword' => $this->faker->words(5, true),
            'meta_description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([0, 1]),
        ];
    }
}
