<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $posts = BlogPost::all();
      if ($posts->count() == 0) {
        $this->command->info('No posts to comment on');
        return;
      }
      $commentCount = (int)$this->command->ask('Enter comment count:', 300);

      $users = User::all();

      Comment::factory()->count($commentCount)->make()->each(function($comment) use ($posts, $users) {
        $comment->blog_post_id = $posts->random()->id;
        $comment->user_id = $users->random()->id;
        $comment->save();
      });
    }
}
