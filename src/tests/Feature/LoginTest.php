<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールが未入力の場合ログインできない()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_パスワードが未入力の場合ログインできない()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_存在しないユーザーはログインできない()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function test_正しい情報でログインできる()
    {
        $user = \App\Models\User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'verification_code' => null,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_未認証ユーザーは認証画面へリダイレクトされる()
    {
        $user = \App\Models\User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'verification_code' => '123456',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');
    }
}
