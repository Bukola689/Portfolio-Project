<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::all()->random()->id,
            'title' => $this->faker->name(),
            'slug' => $this->faker->name(),
            'content' => $this->faker->sentence,
            'keywords' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'thumbnail' => $this->faker->imageUrl($width = 140, $height=300),
            'published' => $this->faker->boolean,

        ];
    }
}
