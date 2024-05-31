<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ 'book_id'    => Book::factory(),
                 'review'     => $this->faker->realText(),
                 'rating'     => $this->faker->numberBetween(1, 5),
                 'created_at' => fake()->dateTimeBetween('-2 years'),
                 'updated_at' => fake()->dateTimeBetween('created_at', 'now'), ];
    }
    
    public function good(): ReviewFactory|Factory
    {
        return $this->state(function(array $attributes) {
            return [ 'rating' => fake()->numberBetween(4, 5), ];
        });
    }
    
    public function average(): ReviewFactory|Factory
    {
        return $this->state(function(array $attributes) {
            return [ 'rating' => fake()->numberBetween(2, 4), ];
        });
    }
    
    public function bad(): ReviewFactory|Factory
    {
        return $this->state(function(array $attributes) {
            return [ 'rating' => fake()->numberBetween(1, 3), ];
        });
    }
}
