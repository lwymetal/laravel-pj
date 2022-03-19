<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

      $minutesAgo = rand(5, 5000);
        return [
          'content' => $this->faker->text(50),
          'created_at' => now()->subMinutes($minutesAgo)
        ];
    }
}
