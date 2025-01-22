<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '歌Share') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss', 'resources/css/app.css'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', '歌Share') }}
                    <span>　ユーザー：{{ Auth::user()->name }}</span>
                </a>
            </div>
             
            <div class="container">
                <ul class="nav nav-underline">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'music']) }}">音楽</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'album']) }}">アルバム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'artist']) }}">アーティスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'playlist']) }}">プレイリスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'recommend']) }}">おすすめ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'user']) }}">ユーザー</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'adv']) }}">広告</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'notification']) }}">通知</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-home', ['tab' => 'another']) }}">その他</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                </ul>
                </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
