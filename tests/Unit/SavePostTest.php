<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class SavePostTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_user_can_save_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.save', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('saved_posts', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
