@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="verify-container">
    <div class="verify-content">
        @if (session('message'))
        <p class="verify-status-message">
            {{ session('message') }}
        </p>
        @endif
        <p class="verify-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a href="{{ route('verification.code') }}" class="verify-start-button">
            認証はこちらから
        </a>

        <form method="POST" action="{{ route('verification.send') }}" class="verify-resend-form">
            @csrf
            <button type="submit" class="verify-resend-link">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection