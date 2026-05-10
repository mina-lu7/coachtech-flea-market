@extends('layouts.app')

@section('title', '住所の変更')

@section('content')
<div class="address-container">
    <h1 class="address-title">住所の変更</h1>

    <form method="POST" action="/purchase/address/{{ $item->id }}" class="address-form">
        @csrf

        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">

        <div class="form-group">
            <label class="form-label">郵便番号</label>
            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code', $address->postal_code ?? '') }}">
            @error('postal_code')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">住所</label>
            <input type="text" name="address" class="form-input" value="{{ old('address', $address->address ?? '') }}">
            @error('address')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">建物名</label>
            <input type="text" name="building" class="form-input" value="{{ old('building', $address->building ?? '') }}">
        </div>

        <button type="submit" class="address-submit-button">更新する</button>
    </form>
</div>
@endsection