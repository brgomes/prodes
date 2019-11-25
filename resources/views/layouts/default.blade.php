<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-100">
    <head>
        <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="{{ asset('fontawesome-5.11.2/css/all.min.css') }}"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="{{ asset('css/style_1.0.css') }}"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <title>Prodes</title>
    </head>
    <body class="d-flex flex-column h-100">
        <header>
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <a class="navbar-brand" href="{{ url('/') }}">Prodes</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto">

                        @if (Auth::check())
                            <li class="nav-item active">
                                <a class="nav-link" href="#"><i class="fas fa-futbol"></i> {{ __('content.apostas') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-trophy"></i> {{ __('content.classificacao') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-history"></i> {{ __('content.historico') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-user"></i> {{ __('content.my-profile') }}</a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbar-language" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="far fa-flag"></i>
                                @if (app()->getLocale() == 'en')
                                    English
                                @elseif (app()->getLocale() == 'pt-BR')
                                    Português
                                @else
                                    Espãnol
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbar-language">
                                <a class="dropdown-item" href="{{ url('/en') }}">
                                    @if (app()->getLocale() == 'en')
                                        <strong>English</strong>
                                    @else
                                        English
                                    @endif
                                </a>
                                <a class="dropdown-item" href="{{ url('/es') }}">
                                    @if (app()->getLocale() == 'es')
                                        <strong>Español</strong>
                                    @else
                                        Español
                                    @endif
                                </a>
                                <a class="dropdown-item" href="{{ url('/pt-BR') }}">
                                    @if (app()->getLocale() == 'pt-BR')
                                        <strong>Português</strong>
                                    @else
                                        Português
                                    @endif
                                </a>
                            </div>
                        </li>

                        @if (Auth::check())
                            @can('admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbar-admin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-tools"></i>
                                        {{ __('content.menu-admin') }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbar-admin">
                                        <a class="dropdown-item" href="#">{{ __('content.menu-rodadas') }}</a>
                                        <a class="dropdown-item" href="#">{{ __('content.menu-usuarios') }}</a>
                                    </div>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-power-off"></i> {{ __('content.logout') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </header>

        <main role="main" class="flex-shrink-0">
            <div class="container">
                @include('includes._alerts')

                @yield('content')
            </div>
        </main>

        <footer class="footer mt-auto py-3 bg-dark text-light">
            <div class="container">
                <span>{{ trans('content.desenvolvido-por') }} <a href="http://www.brgomes.com" target="_blank" class="text-light">brgomes.com</a></span>
            </div>
        </footer>

        <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/funcs_1.0.js') }}"></script>
    </body>
</html>
