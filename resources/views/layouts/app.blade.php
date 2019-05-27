<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'marshak-style.od.ua') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/adminka.css') }}" rel="stylesheet">
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'marshak-style.od.ua') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @auth
                        <li class=" {{ (Route::currentRouteName() == 'admin.photo.index')?'active':'' }}"><a href="{{ route('admin.photo.index') }}">Настройка Фотографий</a></li>
                        <li class=" {{ (Route::currentRouteName() == 'admin.group.index')?'active':'' }}"><a href="{{ route('admin.group.index') }}">Настройка Групп Фотографий</a></li>
                        <li class=" {{ (Route::currentRouteName() == 'admin.message.index')?'active':'' }}"><a href="{{ route('admin.message.index') }}">Настройка Сообщений отзывов</a></li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        @auth
                        @if(Route::currentRouteName() == 'admin.photo.index')

                        <li class="img-butt" data-toggle="modal" data-target="#load_file_modal">
                            <img  style="width: 30px; margin-top: 12px;" src="/img/cloud-upload.png" title="Загрузить новые фотографии.">
                            <span class="hidden-lg hidden-md hidden-sm" style="position: absolute;bottom: 0px;margin-left: 7px;color: aliceblue;">Загрузить новые фотографии.</span>
                        </li>

                        <li class="img-butt" id="dell-all-inactive">
                            <img  style="width: 21px; margin-top: 17px;" src="/img/recikl.png" title="Удалить все неактивные фотографии.">
                            <span class="hidden-lg hidden-md hidden-sm" style="position: absolute; bottom: 0px;margin-left: 7px;margin-bottom: -3px;color: aliceblue;">Удалить все неактивные фотографии.</span>
                        </li>


                        @endif
                        @endauth
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Вход</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('register') }}">Регистрация нового администратора</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выход
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Notifies Alert -->
        <div style="position: fixed;z-index: 2000;width: 100%;bottom: 0px;">
            <div class="row row-fixed-top">
                <div id="notifies_alert" class="col-md-8 col-md-offset-2"></div>
            </div>
        </div>

        @yield('content')

        @include('admin_loader');
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/adminkahelper.js') }}"></script>

</body>
</html>
