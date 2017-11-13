<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                        {{ config('app.name', 'Laravel') }}
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

                        <li>
                            <button type="button" class="btn btn-xs btn-primary navbar-btn" data-toggle="modal" data-target="#load_file_modal">
                            Загрузить фотографии
                            </button>
                        </li>


                        @endif
                        @endauth
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Вход</a></li>
                            <li><a href="{{ route('register') }}">Регистрация</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
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
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/adminkahelper.js') }}"></script>

</body>
</html>
