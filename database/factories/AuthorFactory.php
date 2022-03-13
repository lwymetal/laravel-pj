<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function setProfile() {
      return $this->afterCreating(function ($author) {
        $profile = new Profile();
        $author->profile()->save($profile);
      });
    }
}
