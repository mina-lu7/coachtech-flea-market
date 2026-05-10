<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後に認証メールが送信される()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        Mail::assertSentCount(1);
    }

    public function test_認証画面に遷移できる()
    {
        $response = $this->get('/email/verify');

        $response->assertStatus(200);
    }

    public function test_認証完了後にプロフィール画面へ遷移する()
    {
        $user = \App\Models\User::factory()->create([
            'verification_code' => '123456',
        ]);

        $response = $this->post('/email/verify-code', [
            'verification_code' => '123456',
        ]);

        $response->assertRedirect('/mypage/profile');
    }
}
