<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ArchivePostTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_owner_can_archive_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.archive', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'is_archived' => true,
        ]);
    }
}
