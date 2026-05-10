@extends('layouts.app')

@section('title', '商品の出品')

@section('content')
<div class="auth-container sell-container">
    <h1 class="auth-title">商品の出品</h1>

    <form method="POST" action="/sell" class="auth-form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>商品画像</label>
            <div class="sell-image-upload">
                <div class="sell-image-preview"></div>
                <label class="sell-image-label">
                    画像を選択する
                    <input type="file" name="image" id="image-input" accept="image/*" style="display: none;">
                </label>
            </div>
            @error('image')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <h2 class="sell-section-title">商品の詳細</h2>
        <hr class="sell-divider">

        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-group">
                @isset($categories)
                @foreach($categories as $category)
                <label class="category-label {{ in_array($category->id, old('categories', []), true) ? 'active' : '' }}">
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $category->id }}"
                        {{ in_array($category->id, old('categories', []), true) ? 'checked' : '' }}
                        style="display: none;">
                    <span>{{ $category->name }}</span>
                </label>
                @endforeach
                @else
                {{-- ダミーデータ（動作確認用） --}}
                <label class="category-label"><span>ファッション</span></label>
                <label class="category-label active"><span>インテリア</span></label>
                <label class="category-label"><span>レディース</span></label>
                @endisset
            </div>
            @error('categories')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>商品の状態</label>
            <div class="select-wrapper">
                <select name="condition">
                    <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                    <option value="1" {{ old('condition') == 1 ? 'selected' : '' }}>良好</option>
                    <option value="2" {{ old('condition') == 2 ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="3" {{ old('condition') == 3 ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="4" {{ old('condition') == 4 ? 'selected' : '' }}>状態が悪い</option>
                </select>
            </div>
            @error('condition')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <h2 class="sell-section-title">商品名と説明</h2>
        <hr class="sell-divider">

        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand" value="{{ old('brand') }}">
            @error('brand')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>販売価格</label>
            <div class="price-input-wrapper">
                <span class="price-unit">¥</span>
                <input type="number" name="price" value="{{ old('price') }}">
            </div>
            @error('price')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-button">出品する</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('image-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.querySelector('.sell-image-preview');
                const uploadBox = document.querySelector('.sell-image-upload');

                preview.innerHTML = `<img src="${event.target.result}" alt="プレビュー">`;
                uploadBox.classList.add('has-image');
            };

            reader.readAsDataURL(file);
        });
    });
</script>
@endsection