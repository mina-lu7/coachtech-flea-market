<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール情報が表示される()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'test.jpg',
        ]);

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);

        $purchaseItem = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'name' => '購入商品',
        ]);

        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
            'address_id' => $address->id,
        ]);

        $response = $this->get('/mypage');
        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品');
        $response->assertSee('test.jpg');

        $response = $this->get('/mypage?page=buy');
        $response->assertSee('購入商品');
    }
}
