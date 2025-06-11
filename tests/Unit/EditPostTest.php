<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EditPostTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_owner_can_edit_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $this->actingAs($user);

        $response = $this->put(route('posts.update', $post->id), [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }
}
