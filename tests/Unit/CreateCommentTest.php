<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_authenticated_user_can_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('comments.store'), [
            'post_id' => $post->id,
            'content' => 'Komentar baru',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'content' => 'Komentar baru',
        ]);
    }
}
