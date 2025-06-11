<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreatePostTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_authenticated_user_can_create_post()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'title' => 'Judul Post',
            'description' => 'Deskripsi',
            'reference' => 'Ref',
            'genre_select' => 'Action',
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Judul Post']);

        // Ambil post yang baru dibuat
        $post = \App\Models\Post::where('title', 'Judul Post')->first();

        // Debug: dump the post to see what's actually saved


        // Additional assertEquals assertions
        $this->assertEquals('Judul Post', $post->title);
        $this->assertEquals('Deskripsi', $post->description);
        $this->assertEquals('Ref', $post->reference);

        // Check if the field name is different in the database
        // The field is actually named 'genre' in the database
        $this->assertEquals('Action', $post->genre);

        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals(1, \App\Models\Post::count());

        // Pengecekan string vs integer
        $this->assertEquals((string)$user->id, (string)$post->user_id);
        $this->assertSame((int)$user->id, (int)$post->user_id);
    }
}
