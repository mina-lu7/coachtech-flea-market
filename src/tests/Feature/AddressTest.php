<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_配送先住所が変更できる()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);

        $this->post('/purchase/address/' . $item->id, [
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $address = \App\Models\Address::latest()->first();
        $response = $this->get('/purchase/' . $item->id . '?address_id=' . $address->id);
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);
    }

    public function test_購入時に住所が紐づく()
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

        $this->post('/purchase/' . $item->id, [
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);
    }
}
