<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      $hoursAgo = rand(1, 3000);
        return [
          'title' => $this->faker->sentence(8),
          'content' => $this->faker->paragraphs(3, true),
          'created_at' => now()->subHours($hoursAgo),
          'updated_at' => null
        ];
    }

    public function newtitle() {
      return $this->state([
        'title' => 'New title',
        'content' => 'Default post content'
      ]);
    }
}
