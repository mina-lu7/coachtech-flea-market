<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザーはコメントできる()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント',
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('テストコメント');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    public function test_未ログインユーザーはコメントできない()
    {
        $item = \App\Models\Item::factory()->create();

        $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    public function test_コメント未入力の場合エラーになる()
    {
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/comment/' . $item->id, [
            'content' => '',
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_コメントが255文字を超える場合エラーになる()
    {
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        $this->actingAs($user);

        $longText = str_repeat('あ', 256);

        $response = $this->post('/comment/' . $item->id, [
            'content' => $longText,
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください'
        ]);
    }
}
