<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['scifi', 'horror', 'romance', 'history', 'tech'];

        return [ 'title'      => fake()->sentence(3),
                 'author'     => fake()->name(),
                 'category'   => fake()->randomElement($categories),
                 'created_at' => fake()->dateTimeBetween('-2 years'),
                 'updated_at' => fake()->dateTimeBetween('created_at', 'now')];
    }
}
