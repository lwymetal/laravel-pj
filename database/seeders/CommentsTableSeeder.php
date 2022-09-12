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
      $users = User::all();
      if ($posts->count() == 0 || $users->count() == 0) {
        $this->command->info('Posts and users required to add comments.');
        return;
      }
      $postCommentCount = (int)$this->command->ask('Enter post comment count:', 200);
      $userCommentCount = (int)$this->command->ask('Enter user comment count:', 50);

      Comment::factory()->count($postCommentCount)->make()->each(function($comment) use ($posts, $users) {
        $comment->commentable_id = $posts->random()->id;
        // $comment->commentable_type = 'App\Models\BlogPost';
        $comment->commentable_type = BlogPost::class;
        $comment->user_id = $users->random()->id;
        $comment->save();
      });

      Comment::factory()->count($userCommentCount)->make()->each(function($comment) use ($users) {
        $comment->commentable_id = $users->random()->id;
        // $comment->commentable_type = 'App\Models\BlogPost';
        $comment->commentable_type = User::class;
        $comment->user_id = $users->random()->id;
        $comment->save();
      });

    }
}
