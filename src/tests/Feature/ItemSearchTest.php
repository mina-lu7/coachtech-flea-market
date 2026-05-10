<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品名で部分一致検索ができる()
    {
        \App\Models\Item::factory()->create([
            'name' => 'りんごジュース',
        ]);

        \App\Models\Item::factory()->create([
            'name' => 'みかんジュース',
        ]);

        $response = $this->get('/?keyword=りんご');

        $response->assertSee('りんごジュース');
        $response->assertDontSeeText('みかんジュース');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item1 = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'りんごジュース',
        ]);

        $item2 = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'みかんジュース',
        ]);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        $this->get('/?keyword=りんご');

        $response = $this->get('/?tab=mylist&keyword=りんご');

        $response->assertSee('りんごジュース');
        $response->assertDontSeeText('みかんジュース');
    }
}
