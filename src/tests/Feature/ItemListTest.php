<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $items = \App\Models\Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertSee($items[0]->name);
        $response->assertSee($items[1]->name);
        $response->assertSee($items[2]->name);
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $item = \App\Models\Item::factory()->create();

        $user = \App\Models\User::factory()->create();
        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create(); // ←追加

        $this->actingAs($user);

        \App\Models\Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id, // ←ここ修正（999削除）
            'name' => '他人の商品',
        ]);

        $response = $this->get('/');

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }

    public function test_未認証でも商品一覧が表示される()
    {
        \App\Models\Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }
}
