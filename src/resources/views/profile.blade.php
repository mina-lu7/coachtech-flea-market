@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')

<div class="auth-container">
    <h1 class="auth-title">プロフィール設定</h1>

    <form method="POST" action="/mypage/profile" class="auth-form" enctype="multipart/form-data">
        @csrf

        <div class="profile-image-group">
            <div class="profile-image-preview">
                @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像">
                @else
                <span class="no-image"></span>
                @endif
            </div>
            <input type="file" name="profile_image" id="profile_image" style="display: none;">
            <label for="profile_image" class="profile-image-label">
                画像を選択する
            </label>
        </div>
        @error('profile_image')
        <p class="error-message">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building', $user->building) }}">
            @error('building')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-button">更新する</button>
    </form>
</div>

<script>
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.querySelector('.profile-image-preview');

            preview.innerHTML = `<img src="${event.target.result}" alt="プレビュー">`;
        };

        reader.readAsDataURL(file);
    });
</script>

@endsection