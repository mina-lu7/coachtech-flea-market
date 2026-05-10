@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="mypage-profile-section">
    <div class="profile-flex">
        <div class="profile-image-circle">
            @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="ユーザーアイコン">
            @else
            <div class="no-image-placeholder"></div>
            @endif
        </div>
        <h1 class="profile-user-name">{{ $user->name }}</h1>
        <div class="profile-edit-wrapper">
            <a href="/mypage/profile" class="profile-edit-link">プロフィールを編集</a>
        </div>
    </div>
</div>

<div class="tab-container">
    <div class="tab-item {{ request('page') != 'buy' ? 'active' : '' }}">
        <a href="/mypage">出品した商品</a>
    </div>
    <div class="tab-item {{ request('page') == 'buy' ? 'active' : '' }}">
        <a href="/mypage?page=buy">購入した商品</a>
    </div>
</div>

<div class="item-grid">
    @php
    $displayItems = request('page') == 'buy' ? $purchasedItems : $exhibitedItems;
    @endphp

    @foreach ($displayItems as $displayItem)
    @php
    $item = request('page') == 'buy' ? $displayItem->item : $displayItem;
    @endphp
    <div class="item-card">
        <a href="/item/{{ $item->id }}" class="item-link">
            <div class="item-image-wrapper">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
                @if ($item->purchases && $item->purchases->count() > 0)
                <span class="sold-label">Sold</span>
                @endif
            </div>
            <p class="item-name">{{ $item->name }}</p>
        </a>
    </div>
    @endforeach
</div>
@endsection