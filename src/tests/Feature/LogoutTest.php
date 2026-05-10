<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトができる()
    {
        $user = \App\Models\User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
