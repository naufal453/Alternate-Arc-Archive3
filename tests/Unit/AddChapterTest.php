<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;


class AddChapterTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_add_chapter_with_invalid_post_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $nonExistentPostId = 999;

        $response = $this->post(route('chapters.store'), [
            'post_id' => $nonExistentPostId,
            'title' => 'Chapter 1',
            'content' => 'Isi chapter',
        ]);


        $response->assertfail();
        $response->assertSessionHasErrors(['post_id']);


        $this->assertEquals(0, Chapter::count());
        $this->assertEquals(0, Chapter::where('post_id', $nonExistentPostId)->count());
        $this->assertDatabaseMissing('chapters', [
            'post_id' => $nonExistentPostId,
            'title' => 'Chapter 1',
        ]);


        $this->assertNull(Chapter::where('post_id', $nonExistentPostId)->first());
        $this->assertFalse(Post::where('id', $nonExistentPostId)->exists());
    }


    public function test_cannot_add_chapter_without_required_fields()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('chapters.store'), []);


        $response->assertfail();
        $response->assertSessionHasErrors(['title', 'content', 'post_id']);


        $this->assertEquals(0, Chapter::count());
        $this->assertEquals(0, Chapter::where('post_id', $post->id)->count());


        $this->assertTrue(Post::where('id', $post->id)->exists());
        $this->assertEquals(1, Post::count());
        $this->assertNull(Chapter::first());
    }

    public function test_can_add_multiple_chapters_to_same_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $this->post(route('chapters.store'), [
            'post_id' => $post->id,
            'title' => 'Chapter 1',
            'content' => 'First chapter content',
        ]);

        $this->post(route('chapters.store'), [
            'post_id' => $post->id,
            'title' => 'Chapter 2',
            'content' => 'Second chapter content',
        ]);

        $this->assertEquals(2, Chapter::where('post_id', $post->id)->count());
        $this->assertEquals(2, Chapter::count());
    }

    public function test_cannot_add_chapter_with_empty_title()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('chapters.store'), [
            'post_id' => $post->id,
            'title' => '',
            'content' => 'Content without title',
        ]);


        $response->assertfail();
        $response->assertSessionHasErrors(['title']);


        $this->assertEquals(0, Chapter::count());
        $this->assertEquals(0, Chapter::where('post_id', $post->id)->count());


        $this->assertTrue(Post::where('id', $post->id)->exists());
    }
    public function test_cannot_add_chapter_with_empty_content()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('chapters.store'), [
            'post_id' => $post->id,
            'title' => 'Chapter with empty content',
            'content' => '',
        ]);


        $response->assertfail();
        $response->assertSessionHasErrors(['content']);


        $this->assertEquals(0, Chapter::count());
        $this->assertEquals(0, Chapter::where('post_id', $post->id)->count());


        $this->assertTrue(Post::where('id', $post->id)->exists());
    }
}
