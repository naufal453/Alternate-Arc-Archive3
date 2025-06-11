<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_owner_can_delete_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($user)->for($post)->create();
        $this->actingAs($user);

        $response = $this->delete(route('comments.destroy', $comment->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
