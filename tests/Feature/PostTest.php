<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoPostsFoundWhenDBEmpty()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No blog posts yet');
    }

    public function testSee1PostWhen1Exists() {
      $user = $this->user();
      $this->actingAs($user);

      $post = $this->createDummyPost();
      $response = $this->get('/posts');
      $response->assertSeeText($post->title);
      $response->assertSeeText('No comments');

      $this->assertDatabaseHas('blog_posts', ['title' => $post->title]);
    }

    public function testSee1PostWithComments() {
      $user = $this->user();
      $this->actingAs($user);

      $post = $this->createDummyPost();
      $comments = Comment::factory()->count(3)->create([
        'user_id' => $user->id,
        'commentable_id' => $post->id, 
        'commentable_type' => 'App\Models\BlogPost'
      ]);
      $response = $this->get('/posts');
      $response->assertSeeText('3 comments');
    }

    public function testStoreValid() {
      $user = $this->user();

      $params = ['title' => 'Valid title', 'content' => 'Valid content 10+ chars'];

      $this->actingAs($user)
        ->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post created');
    }

    public function testStoreFail() {
      $user = $this->user();

      $params = ['title' => 'x', 'content' => 'x'];

      $this->actingAs($user)
        ->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('errors');

      $messages = session('errors')->getMessages();
      $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
      $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid() {
      $user = $this->user();
      $this->actingAs($user);

      $post = $this->createDummyPost($user->id);

      $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => $post->title]); 
      // tutorial has $post->toArray() but timestamp comparison fails

      $params = ['title' => 'Updated title', 'content' => 'Updated content'];

      $this->put('/posts/'.$post->id, $params)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post updated');
      $this->assertDatabaseMissing('blog_posts', ['title' => 'Post title']);
      $this->assertDatabaseHas('blog_posts', ['title' => 'Updated title']);
    }

    public function testDeletePost() {
      $user = $this->user();

      $this->actingAs($user);

      $post = $this->createDummyPost($user->id);
      $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => $post->title]); 
      $this->delete('/posts/'.$post->id)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post deleted');
      // $this->assertDatabaseMissing('blog_posts', ['id' => $post->id, 'title' => $post->title]); 
      $this->assertSoftDeleted('blog_posts', ['id' => $post->id, 'title' => $post->title]);
    }

    private function createDummyPost($user_id = null) {
      // $post = new BlogPost();
      // $post->title = 'Post title';
      // $post->content = 'Post content';
      // $post->save();

      // return BlogPost::factory()->newtitle()->create();

      return BlogPost::factory()->create(['user_id' => $user_id ?? $this->user()->id]);

      // return $post;
    }
}
