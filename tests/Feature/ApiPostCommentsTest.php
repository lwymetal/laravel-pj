<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BlogPost;
use App\Models\Comment;

class ApiPostCommentsTest extends TestCase
{
  use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testNewPostHasNoComments() {
      // $post = BlogPost::factory()->create(['user_id' => $this->user()->id]); // added to TestCase
      $this->blogPost();
      $response = $this->json('GET', 'api/v1/posts/1/comments');
      $response->assertStatus(200)
        ->assertJsonStructure(['data', 'links', 'meta'])
        ->assertJsonCount(0, 'data');

    }

    public function testPostHas10Comments() {
      $this->blogPost()
        ->each(function (BlogPost $post) {
          $post->comments()->saveMany(
            $comments = Comment::factory()->count(10)->make(['user_id' => $this->user()->id])
          );
        });

      $response = $this->json('GET', 'api/v1/posts/2/comments'); // * 2 * didn't reset db
      $response->assertStatus(200)
        ->assertJsonStructure([
          'data' => ['*' => ['comment_id', 'content', 'created_at', 'updated_at', 'user' => ['id', 'name']]], 
            'links', 'meta'
          ])
        ->assertJsonCount(10, 'data');
  
    }

    public function testAddCommentWithoutAuthentication() {
      $this->blogPost();
      $response = $this->json('POST', 'api/v1/posts/3/comments', [
        'content' => 'test text'
      ]);
      // $response->assertStatus(401);
      $response->assertUnauthorized();
    }

    public function testAddCommentWithAuthentication() {
      $this->blogPost();
      $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/4/comments', [
        'content' => 'test text'
      ]);
      $response->assertStatus(201);
    }

    public function testAddCommentWithInvalidData() {
      $this->blogPost();
      $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/5/comments', [
        // empty array
      ]);
      $response->assertStatus(422); // invalid data
    }
}
