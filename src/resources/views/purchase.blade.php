@extends('layouts.app')

@section('title', '商品購入')

@section('content')

<div class="purchase-container">

    <div class="purchase-main">

        <div class="purchase-item-info">
            <div class="purchase-item-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
            </div>
            <div class="purchase-item-details">
                <h1 class="purchase-item-name">{{ $item->name }}</h1>
                <p class="purchase-item-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        <hr class="purchase-divider">

        <form id="purchase-form" method="POST" action="{{ url('/checkout') }}">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            <div class="purchase-block">
                <p class="purchase-label">支払い方法</p>

                <div class="select-wrapper">
                    <select name="payment_method" id="payment_method_select">
                        <option value="" disabled {{ old('payment_method', request('payment_method')) ? '' : 'selected' }}>選択してください</option>
                        <option value="convenience" {{ old('payment_method', request('payment_method')) === 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                        <option value="card" {{ old('payment_method', request('payment_method')) === 'card' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                </div>

                @error('payment_method')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <hr class="purchase-divider">

            <div class="purchase-block">
                <div class="purchase-header-row">
                    <p class="purchase-label">配送先</p>
                    <a href="/purchase/address/{{ $item->id }}?payment_method=" id="address-change-link" class="address-change-link">変更する</a>
                </div>
                <div class="address-display">
                    <p>〒 {{ $address->postal_code }}</p>
                    <p>{{ $address->address }}</p>
                    <p>{{ $address->building }}</p>
                </div>
            </div>

            <hr class="purchase-divider">

        </form>
    </div>

    <aside class="purchase-sidebar">
        <div class="summary-card">
            <div class="summary-content">
                <div class="summary-item-box">
                    <div class="summary-row">
                        <span class="summary-label">商品代金</span>
                        <span class="summary-value">¥ {{ number_format($item->price) }}</span>
                    </div>
                </div>

                <div class="summary-item-box">
                    <div class="summary-row">
                        <span class="summary-label">支払い方法</span>
                        <span id="selected-payment-method" class="summary-value"></span>
                    </div>
                </div>
            </div>

            <button type="submit" form="purchase-form" class="purchase-submit-button">
                購入する
            </button>
        </div>
    </aside>

</div>

<script>
    const paymentSelect = document.getElementById('payment_method_select');
    const paymentText = document.getElementById('selected-payment-method');
    const addressLink = document.getElementById('address-change-link');

    function updatePaymentDisplay() {
        const selectedValue = paymentSelect.value;
        const selectedText = paymentSelect.options[paymentSelect.selectedIndex]?.text ?? '';

        paymentText.textContent = selectedText;

        addressLink.href = `/purchase/address/{{ $item->id }}?payment_method=${selectedValue}`;
    }

    paymentSelect.addEventListener('change', updatePaymentDisplay);

    updatePaymentDisplay();
</script>
@endsection