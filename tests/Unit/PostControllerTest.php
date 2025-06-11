<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;



    public function test_index_returns_posts()
    {
        Post::factory()->count(3)->create();

        $response = $this->get(route('home.index'));
        $response->assertStatus(200);
        $response->assertViewHas('posts');
    }

    public function test_create_requires_authentication()
    {
        $response = $this->get(route('posts.create'));
        $response->assertRedirect('/login');
    }

    public function test_store_creates_post()
    {
        $this->withoutMiddleware(); // hanya untuk test ini
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'title' => 'Test Post',
            'description' => 'Test Description',
            'reference' => 'Test Ref',
            'genre_select' => 'Action', // langsung string, tanpa model Genre
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
        $response->assertRedirect();
    }

    public function test_show_returns_404_if_post_not_found()
    {
        $response = $this->get(route('posts.show', 999));
        $response->assertStatus(404);
    }

    public function test_edit_requires_authorization()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(); // bukan post milik user

        $this->actingAs($user);
        $response = $this->get(route('posts.edit', $post->id));
        $response->assertStatus(403);
    }

    public function test_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        $response = $this->put(route('posts.update', $post->id), [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
        ]);

        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
        $response->assertRedirect();
    }

    public function test_destroy_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        $response = $this->delete(route('posts.destroy', $post->id));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $response->assertRedirect();
    }

    public function test_like_and_unlike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.like', $post->id));
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->post(route('posts.unlike', $post->id));
        $this->assertDatabaseMissing('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }
}
