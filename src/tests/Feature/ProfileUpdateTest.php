<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール編集画面に初期値が表示される()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'test.jpg',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertSee('テストユーザー');
        $response->assertSee('test.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
    }
}
