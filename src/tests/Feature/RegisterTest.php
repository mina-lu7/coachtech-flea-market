<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が未入力の場合エラーになる()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください'
        ]);
    }

    public function test_メールが未入力の場合エラーになる()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_パスワードが未入力の場合エラーになる()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_パスワードが8文字未満の場合エラーになる()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください'
        ]);
    }

    public function test_パスワード確認が一致しない場合エラーになる()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password999',
        ]);

        $response->assertSessionHasErrors([
            'password_confirmation' => 'パスワードと一致しません'
        ]);
    }

    public function test_正常に会員登録できる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
