<style>

</style>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{asset('storage/logo.ico')}}" />
    <title>PT. Lundin - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a href="{{ url('/') }}">
                    <img src="{{asset('storage/logo.png')}}" alt="PT. Lundin" style="max-width:100px;margin-right:20px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                            <li class="navbar"><a href="{{ url('/') }}">Beranda</a></li>
                            @if (Auth::user()->role==='manajer'||Auth::user()->role==='admin')
                                <li class="navbar"><a class=".navbar-men" href="{{ url('/') }}">Proyek</a></li>
                            @endif
                            @if (Auth::user()->role==='admin')
                                <li class="navbar"><a href="{{ url('/') }}">Pekerjaan</a></li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Pegawai <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" class="logout" href="">
                                           Daftar Pegawai
                                        </a>
                                        <a class="dropdown-item" class="logout" href="">
                                            Jabatan
                                        </a>
                                        <a class="dropdown-item" class="logout" href="">
                                            Kelompok Kerja
                                        </a>
                                    </div>
                                </li>
                                <li class="navbar"><a href="{{ url('/') }}">Jam Kerja</a></li>
                            @endif
                            @if (Auth::user()->role==='manajer'||Auth::user()->role==='admin')
                                <li class="navbar"><a href="{{ url('/') }}">Presensi Proyek</a></li>
                            @endif
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" class="logout" href="{{ route('logout') }}"
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
            <div class="container">
                <div class="bg-white shadow-sm">
                    <br>
                    @yield('content')
                    <br>
                </div>
            </div>
        </main>
    </div>
</body>
<!-- Footer -->
<footer class="page-footer font-small pt-4">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">©{{ now()->year }} Copyright:
      <a href="https://mdbootstrap.com/"> PT. Lundin</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->
</html>
