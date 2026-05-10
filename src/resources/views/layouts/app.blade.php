<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>@yield('title')</title>
</head>

<body>

    <header class="header">
        <div class="header-logo">
            <a href="/">
                <img src="{{ asset('storage/logo/title_logo.png') }}" alt="ロゴ">
            </a>
        </div>

        @if (
        !request()->is('login') &&
        !request()->is('register') &&
        !request()->is('email/verify')
        )
        <div class="header-search">
            <form method="GET" action="/">
                @if(request('tab'))
                <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
            </form>
        </div>

        <nav class="header-nav">
            @auth
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="/mypage" class="nav-link">マイページ</a>
            @else
            <a href="/login" class="nav-link">ログイン</a>
            <a href="/mypage" class="nav-link">マイページ</a>
            @endauth
            <a href="/sell" class="sell-button">出品</a>
        </nav>
        @endif
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>