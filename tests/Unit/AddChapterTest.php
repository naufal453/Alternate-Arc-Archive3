<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AddChapterTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_authenticated_user_can_add_chapter()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('chapters.store'), [
            'post_id' => $post->id,
            'title' => 'Chapter 1',
            'content' => 'Isi chapter',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chapters', [
            'post_id' => $post->id,
            'title' => 'Chapter 1',
        ]);

        // Additional assertEquals assertions
        $chapter = Chapter::where('post_id', $post->id)->first();
        $this->assertEquals($post->id, $chapter->post_id);
        $this->assertEquals('Chapter 1', $chapter->title);
        $this->assertEquals('Isi chapter', $chapter->content);
        $this->assertEquals(1, Chapter::count());
    }
}
