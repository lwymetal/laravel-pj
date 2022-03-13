<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoPostsFoundWhenDBEmpty()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No posts found');
    }

    public function testSee1PostWhen1Exists() {
      $post = $this->createDummyPost();
      $response = $this->get('/posts');
      $response->assertSeeText('Post title');
      $response->assertSeeText('No comments');

      $this->assertDatabaseHas('blog_posts', ['title' => 'Post title']);
    }

    public function testSee1PostWithComments() {
      $post = $this->createDummyPost();
      $comments = Comment::factory()->count(3)->create(['blog_post_id' => $post->id]);
      $response = $this->get('/posts');
      $response->assertSeeText('3 comments');
    }

    public function testStoreValid() {
      $params = ['title' => 'Valid title', 'content' => 'Valid content 10+ chars'];

      $this->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post created');
    }

    public function testStoreFail() {
      $params = ['title' => 'x', 'content' => 'x'];

      $this->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('errors');

      $messages = session('errors')->getMessages();
      $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
      $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid() {
      $post = $this->createDummyPost();

      $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => 'Post title']); // tutorial has $post->toArray() but timestamp comparison fails

      $params = ['title' => 'Updated title', 'content' => 'Updated content'];

      $this->put('/posts/'.$post->id, $params)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post updated');
      $this->assertDatabaseMissing('blog_posts', ['title' => 'Post title']);
      $this->assertDatabaseHas('blog_posts', ['title' => 'Updated title']);
    }

    public function testDeletePost() {
      $post = $this->createDummyPost();
      $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => 'Post title']); 
      $this->delete('/posts/'.$post->id)
        ->assertStatus(302)
        ->assertSessionHas('status');

      $this->assertEquals(session('status'), 'Post deleted');
      $this->assertDatabaseMissing('blog_posts', ['id' => $post->id, 'title' => 'Post title']); 
    }

    private function createDummyPost() {
      // $post = new BlogPost();
      // $post->title = 'Post title';
      // $post->content = 'Post content';
      // $post->save();

      // return BlogPost::factory()->newtitle()->create();

      return BlogPost::factory()->create();

      // return $post;
    }
}
