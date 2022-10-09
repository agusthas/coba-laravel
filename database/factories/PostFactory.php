<?php

namespace Database\Factories;

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
            'title' => $this->faker->sentence(mt_rand(2, 8)),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->paragraph(),
            'body' => collect($this->faker->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->join(''),
            'category_id' => mt_rand(1, 2), // static, karena kita tau berapa banyak category
            'user_id' => mt_rand(1, 3) // static, karena kita tau berapa banyak category
        ];
    }
}
