<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $likedItem = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'いいね商品',
        ]);

        $notLikedItem = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '未いいね商品',
        ]);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('いいね商品');
        $response->assertDontSeeText('未いいね商品');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '購入済み商品',
        ]);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        \App\Models\Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('テスト商品');
    }
}
