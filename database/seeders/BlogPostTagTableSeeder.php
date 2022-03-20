<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $tagCount = Tag::all()->count();
      if ($tagCount == 0) {
        $this->command->info('No tags found, skipping assignment');
        return;
      }
      $minTags = (int)$this->command->ask('Minimum tags per post: ', 0);
      $maxTags = min((int)$this->command->ask('Maximum tags per post: ', $tagCount), $tagCount);

      BlogPost::all()->each(function (BlogPost $post) use($minTags, $maxTags) {
        $take = random_int($minTags, $maxTags);
        $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
        $post->tags()->sync($tags); // sync saves automatically
      });
    }
}
