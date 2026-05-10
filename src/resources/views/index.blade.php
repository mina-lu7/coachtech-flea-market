@extends('layouts.app')

@section('title', '商品一覧')

@section('content')

<div class="tab-container">
    <div class="tab-item {{ request('tab') != 'mylist' ? 'active' : '' }}">
        <a href="/">おすすめ</a>
    </div>
    <div class="tab-item {{ request('tab') == 'mylist' ? 'active' : '' }}">
        <a href="/?tab=mylist{{ request('keyword') ? '&keyword=' . request('keyword') : '' }}">マイリスト</a>
    </div>
</div>

<div class="item-grid">
    @foreach ($items as $item)
    @if(Auth::check() && $item->user_id === Auth::id()) @continue @endif
    <div class="item-card">
        <a href="/item/{{ $item->id }}" class="item-link">
            <div class="item-image-wrapper">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
                @if ($item->purchases->count() > 0)
                <span class="sold-label">Sold</span>
                @endif
            </div>
            <p class="item-name">{{ $item->name }}</p>
        </a>
    </div>
    @endforeach
</div>

@endsection