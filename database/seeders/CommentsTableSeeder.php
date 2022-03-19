<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $posts = \App\Models\BlogPost::all();
      if ($posts->count() == 0) {
        $this->command->info('No posts to comment on');
        return;
      }
      $commentCount = (int)$this->command->ask('Enter comment count:', 300);

      Comment::factory()->count($commentCount)->make()->each(function($comment) use ($posts) {
        $comment->blog_post_id = $posts->random()->id;
        $comment->save();
      });
    }
}
