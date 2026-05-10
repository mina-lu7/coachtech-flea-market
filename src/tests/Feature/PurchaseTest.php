<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品を購入できる()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post('/purchase/' . $item->id, [
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/');

        $response = $this->get('/');
        $response->assertSee('Sold');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_購入した商品はSoldと表示される()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '購入商品',
        ]);

        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->post('/purchase/' . $item->id, [
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_購入した商品はプロフィールに表示される()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '購入商品',
        ]);

        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->post('/purchase/' . $item->id, [
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $response = $this->get('/mypage?page=buy');

        $response->assertSee('購入商品');
    }
}
