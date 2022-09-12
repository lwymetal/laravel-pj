<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageWorksCorrectly()
    {
      $response = $this->get('/');
      $response->assertSeeText('Hello World');
      $response->assertSeeText('Welcome');
    }

    public function testContactPageWorksCorrectly() {
      $response = $this->get('/contact');
      $response->assertSeeText('Contact');
      $response->assertSeeText('Hello this is contact!');
    }

}
