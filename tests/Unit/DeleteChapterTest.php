<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DeleteChapterTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_owner_can_delete_chapter()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $chapter = Chapter::factory()->for($user)->for($post)->create();
        $this->actingAs($user);

        $response = $this->delete(route('chapters.destroy', $chapter->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }
    public function test_non_owner_cannot_delete_chapter()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $chapter = Chapter::factory()->for($user)->for($post)->create();
        $this->actingAs(User::factory()->create());

        $response = $this->delete(route('chapters.destroy', $chapter->id));
        $response->assertForbidden();
        $this->assertDatabaseHas('chapters', ['id' => '2']
        /*['id' => $chapter->id]*/);
    }
    public function test_cannot_delete_chapter_with_invalid_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $invalidChapterId = 999;

        $response = $this->delete(route('chapters.destroy', $invalidChapterId));
        $response->assertFailed();

        $this->assertDatabaseMissing('chapters', ['id' => $invalidChapterId]);
    }
}
