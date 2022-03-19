<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BlogPost;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $postCount = (int)$this->command->ask('Enter post count:', 80);
      $users = \App\Models\User::all();
      BlogPost::factory()->count($postCount)->make()->each(function($post) use ($users) {
        $post->user_id = $users->random()->id;
        $post->save();
      });
    }
}
