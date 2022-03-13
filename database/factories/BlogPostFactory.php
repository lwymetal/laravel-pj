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
        return [
          'title' => $this->faker->sentence(8),
          'content' => $this->faker->paragraphs(3, true)
        ];
    }

    public function newtitle() {
      return $this->state([
        'title' => 'New title',
        'content' => 'Default post content'
      ]);
    }
}
