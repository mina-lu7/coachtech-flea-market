<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_支払い方法が反映される()
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
            'payment_method' => 'convenience',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
