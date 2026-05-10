<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねができる()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->post('/like/' . $item->id);

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('1');

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_いいねを解除できる()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->post('/like/' . $item->id);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
