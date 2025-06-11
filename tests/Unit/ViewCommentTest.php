<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ViewCommentTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_post_detail_displays_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($user)->for($post)->create([
            'content' => 'Ini komentar pengujian'
        ]);

        $response = $this->get(route('posts.show', $post->id));
        $response->assertStatus(200);
        $response->assertSee('Ini komentar pengujian');
        $response->assertSee($user->username);
    }
}
