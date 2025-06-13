<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagementStoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_user_can_archive_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['is_archived' => false]);
        $this->actingAs($user);

        $response = $this->post(route('posts.archive', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'is_archived' => true,
        ]);
    }

    public function test_user_can_unarchive_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['is_archived' => true]);
        $this->actingAs($user);

        $response = $this->post(route('posts.unarchive', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'is_archived' => false,
        ]);
    }

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

    public function test_user_can_unsave_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $user->savedPosts()->attach($post->id);
        $this->actingAs($user);

        $response = $this->post(route('posts.unsave', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('saved_posts', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
