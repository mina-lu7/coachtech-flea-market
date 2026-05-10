@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<div class="item-detail-container">
    <div class="item-main">
        <div class="item-image-box">
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
        </div>
    </div>

    <div class="item-info">
        <h1 class="item-detail-name">{{ $item->name }}</h1>
        <p class="item-brand">{{ $item->brand }}</p>
        <p class="item-price">¥{{ number_format($item->price) }}<span>(税込)</span></p>

        <div class="item-action">

            {{-- いいね --}}
            <div class="action-group">

                @auth
                <form method="POST" action="/like/{{ $item->id }}">
                    @csrf
                    <button type="submit" class="icon-button">
                        @if ($item->likes->where('user_id', auth()->id())->count())
                        <img src="{{ asset('storage/logo/heart_active.png') }}" alt="いいね">
                        @else
                        <img src="{{ asset('storage/logo/heart.png') }}" alt="いいね">
                        @endif
                    </button>
                </form>
                @else
                <a href="/login" class="icon-button">
                    <img src="{{ asset('storage/logo/heart.png') }}" alt="いいね">
                </a>
                @endauth

                <p>{{ $item->likes->count() }}</p>

            </div>

            {{-- コメント --}}
            <div class="action-group">
                <a href="#comments" class="icon-button">
                    <img src="{{ asset('storage/logo/comment.png') }}" alt="コメント">
                </a>
                <p>{{ $item->comments->count() }}</p>
            </div>

        </div>

        @if ($item->purchases->count() > 0)
        <span class="purchase-link sold-button">Sold</span>
        @else
        <a href="/purchase/{{ $item->id }}" class="purchase-link">購入手続きへ</a>
        @endif

        <div class="detail-section">
            <h2>商品説明</h2>
            <p>{{ $item->description }}</p>
        </div>

        <div class="detail-section">
            <h2>商品の情報</h2>
            <div class="detail-row">
                <h3>カテゴリー</h3>
                <div class="category-tags">
                    @foreach ($item->categories as $category)
                    <span class="category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="detail-row">
                <h3>商品の状態</h3>
                <p>
                    @if ($item->condition == 1)
                    良好
                    @elseif ($item->condition == 2)
                    目立った傷や汚れなし
                    @elseif ($item->condition == 3)
                    やや傷や汚れあり
                    @elseif ($item->condition == 4)
                    状態が悪い
                    @endif
                </p>
            </div>
        </div>

        <div class="comment-section" id="comments">
            <h2>コメント({{ $item->comments->count() }})</h2>

            @foreach ($item->comments as $comment)
            <div class="comment-item">
                <div class="comment-user">
                    <div class="user-icon">
                        @if($comment->user->profile_image)
                        <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="ユーザー画像">
                        @endif
                    </div>
                    <p class="user-name">{{ $comment->user->name }}</p>
                </div>
                <div class="comment-content">
                    {{ $comment->content }}
                </div>
            </div>
            @endforeach

            <form method="POST" action="/comment/{{ $item->id }}" class="comment-form">
                @csrf
                <p>商品へのコメント</p>
                <textarea name="content">{{ old('content') }}</textarea>

                @error('content')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <button type="submit" class="submit-button">コメントを送信する</button>
            </form>

        </div>
    </div>
</div>
@endsection