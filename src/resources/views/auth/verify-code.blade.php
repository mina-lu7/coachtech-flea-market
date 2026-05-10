@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="verify-container">
    <div class="verify-content">
        <p class="verify-text">
            メールに送信された認証コードを入力してください
        </p>

        <form method="POST" action="/email/verify-code" class="verify-form">
            @csrf

            <input type="text" name="verification_code" class="verify-input" maxlength="6">

            <button type="submit" class="verify-button">
                認証する
            </button>
        </form>

        @error('verification_code')
        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>
</div>
@endsection