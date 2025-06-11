<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ViewPostTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_can_view_post_detail()
    {
        $post = Post::factory()->create();
        $response = $this->get(route('posts.show', $post->id));
        $response->assertStatus(200);
        $response->assertViewIs('home.post.detail');
    }
}
