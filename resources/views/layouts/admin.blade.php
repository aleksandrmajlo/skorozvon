<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')-{{ config('app.name', '') }}</title>
    <script src="{{ mix('js/app.js') }}" defer></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9e665f9e92.js" crossorigin="anonymous"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboardadmin">DASHBOARD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/reports">ОТЧЁТЫ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contacts">КОНТАКТЫ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/operators">ОПЕРАТОРЫ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/settings">НАСТРОЙКИ</a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="/settings">ПРОФИЛЬ</a>--}}
{{--                    </li>--}}
                    @guest
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                ВЫХОД
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
                <form class="form-inline  my-2 my-lg-0" method="get" action="{{ route('contacts.index') }}">
                    <input name="q" required class="form-control mr-sm-2" type="search" placeholder="БЫСТРЫЙ ПОИСК ПО: ID / INN / PHONE" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">ПОИСК</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
