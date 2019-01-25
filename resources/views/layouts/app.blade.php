<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
{{--    <script src="{{ asset('js/pekeUpload/js/pekeUpload.js') }}"></script>--}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/noty.css') }}" rel="stylesheet">
    <script src="{{ asset('js/preloader.js') }}" defer></script>
    @yield('header-js')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    <img src="{{ env('APP_LOGO') }}" width="30"> {{ env('APP_NAME') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item" style="position: relative">
                                <a class="nav-link" href="{{ route('notifications.list') }}"><i class="fa fa-fw fa-bell fa-fw"></i></a>
                                @if ( $total = (new \App\Http\Controllers\NotificationController())->number() )
                                    <div style="position: absolute; bottom: 2px; right: 0; background-color: #F00; width: 16px; height: 16px; font-size: 12px; line-height: 16px; text-align: center; color: #FFF; border-radius: 8px; opacity: .7">{{ $total }}</div>
                                @endif
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ticket.create') }}"><i class="fa fa-plus-circle fa-fw"></i> Chamado</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user-circle fa-fw"></i> <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if (\Auth::user()->is_controller) <a class="dropdown-item" href="{{ route('controller_room') }}">Controladoria</a>@endif
                                    <a class="dropdown-item" href="{{ route('password.change') }}">Alterar Senha</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <div id="preloader" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 999; background-color: #FFF">
        <div style="position: fixed; top: 50%; left: 50%; margin-left: -193px; margin-top: -193px; z-index: 1000; text-align: center; width: 386px; height: 386px;">
            <img src="/images/logo.png"><br>
            <h4>Carregando...</h4>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/footer.js') }}?v={{ microtime() }}" ></script>
    @yield('footer-js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!Notification) {
                alert('Seu browser não permite notificações, tente usar FIREFOX ou CHROME');
                return;
            }

            if (Notification.permission !== "granted")
                Notification.requestPermission();
        });

        function notifyMe(title, body, link) {
            if (Notification.permission !== "granted")
                Notification.requestPermission();
            else {
                var notification = new Notification(title, {
                    icon: '{{ asset('images/logo.png') }}',
                    body: body
                });

                notification.onclick = function () {
                    window.location.href = link
                };

            }

        }

        // setInterval(function() {
        //     getNotifications();
        // }, 120000);
        function getNotifications() {
            $.get('/notifications')
                .done(function(e) {
                    if ( e.length > 0) {
                        notifyMe(
                            e[0].title,
                            e[0].text,
                            e[0].url
                        );
                    }
                })
        }

        // getNotifications();
    </script>
</body>
</html>
