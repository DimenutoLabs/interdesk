<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/default.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/gijgo/css/gijgo.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/noty/noty.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/starrr/css/starrr.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/scel-image-upload/css/scel-image-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/noty.css') }}" rel="stylesheet">
</head>
<body>

    <div id="preloader" style="width: 100%; height: 100%; position: fixed; background: #FFF; z-index: 10000"></div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
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
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link btn btn-success" style="color: #FFF" href="{{ route('ticket.create') }}"><i class="fa fa-fw fa-plus-circle"></i> Novo Chamado</a>--}}
{{--                            </li>--}}

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="nav-link" href="{{ route('ticket.create') }}"><i class="fa fa-fw fa-plus-circle"></i> Novo Chamado</a>

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
    <script src="{{ asset('vendor/jquery/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/gijgo/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('vendor/noty/noty.min.js') }}"></script>

    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendor/starrr/js/starrr.js') }}"></script>
    <script src="{{ asset('vendor/scel-image-upload/js/scel-image-upload.js') }}"></script>


<script>

    var validation = $('#new_ticket_form').validate({
        errorPlacement: function (error, element) {
        },
        invalidHandler: function () {
            var errors = validation.errorList;
            var message = [];
            for (var i in errors) {
                var elt = $(errors[i].element);
                message.push(elt.attr('data-field_name'));
            }
            if ( message.length > 0 ) {
                new Noty({
                    text: message.join("<br>"),
                    layout: 'topCenter',
                    timeout: message.length * 1500,
                    progressBar: true,
                    type: 'error',
                    theme: 'bootstrap-v4'
                }).show();
            }
        },
        ignore: [],
        rules: {
            small_title : {
                required : true
            },
            prior : {
                required : true
            },
            department : {
                required : function(element) {
                    return $('#assigned_to').val() == "";
                }
            },
            content : {
                required : true
            }
        }
    });

    $('.date').mask("00/00/0000", {clearIfNotMatch: true, placeholder: "__ /__ /____"});
    $('.time').mask("00:00", {clearIfNotMatch: true, placeholder: "__ : __"});



    $("textarea").editor({uiLibrary: 'bootstrap'});
    $(".selectpicker").select2({
        allowClear: true,
        placeholder: '---'
    });
    $('.selectpicker-multiple').select2({
        allowClear: true,
        placeholder: '---',
        tags: true
    });
    $('[data-toggle="tooltip"]').tooltip();

    var validation = $('#edit_ticket_form').validate({
        errorPlacement: function (error, element) {
        },
        invalidHandler: function () {
            var errors = validation.errorList;
            var message = [];
            for (var i in errors) {
                var elt = $(errors[i].element);
                message.push(elt.attr('data-field_name'));
            }
            if ( message.length > 0 ) {
                new Noty({
                    text: message.join("<br>"),
                    layout: 'topCenter',
                    timeout: message.length * 1500,
                    progressBar: true,
                    type: 'error',
                    theme: 'bootstrap-v4'
                }).show();
            }
        },
        ignore: [],
        rules: {
            reply_content : {
                required : true
            }
        }
    });
</script>
<script defer>
    $(function() {
        $('#preloader').fadeOut(500);
    });
</script>


@yield('footer-js')
</body>
</html>
