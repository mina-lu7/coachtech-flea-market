@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="auth-container">
    <h1 class="auth-title">ログイン</h1>

    <form method="POST" action="/login" class="auth-form">
        @csrf

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-button">ログインする</button>
    </form>

    <a href="/register" class="auth-link">会員登録はこちら</a>
</div>
@endsection