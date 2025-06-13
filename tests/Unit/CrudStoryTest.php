<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudStoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_story()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'title' => 'Judul Story',
            'description' => 'Deskripsi Story',
            'reference' => 'Referensi',
            'genre_select' => 'Action',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Judul Story']);
    }

    public function test_user_can_view_story()
    {
        $post = Post::factory()->create();
        $response = $this->get(route('posts.show', $post->id));
        $response->assertStatus(200);
        $response->assertViewIs('home.post.detail');
        $response->assertSeeText(/*$post->title*/'Haha'); // Use assertSee instead of assertSeeTitle
        $response->assertSee('123');
    }

    public function test_user_can_update_story()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $this->actingAs($user);

        $response = $this->put(route('posts.update', $post->id), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
    }

    public function test_user_can_delete_story()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $this->actingAs($user);

        $response = $this->delete(route('posts.destroy', $post->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
