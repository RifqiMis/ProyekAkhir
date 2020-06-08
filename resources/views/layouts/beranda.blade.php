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
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/all.js') }}" defer></script> --}}

    {{-- Chart --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" type="application/javascript" charset="utf-8">
    </script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script>
        // Open Modal
        $(document).on('ajaxComplete ready', function () {
            $('.modalMd').off('click').on('click', function () {
                $('#modalMdContent').load($(this).attr('value'));
                $('#modalMdTitle').html($(this).attr('title'));
            });
        });

        // print div
        function printDiv(divName) {
             var printContents = document.getElementById(divName).innerHTML;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
             location.reload()
            }  
        </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
    <div>
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
                            <li class="nav-item {{ Request::segment(1) === 'home' ? 'active' : null }}"><a class="nav-link" href="{{ url('/home') }}">Beranda</a></li>
                            @if (Auth::user()->role==='manajer'||Auth::user()->role==='admin')
                                <li class="nav-item {{ Request::segment(1) === 'proyek' ? 'active' : null }}"><a class="nav-link" class=".navbar-men" href="{{ url('/proyek') }}">Proyek</a></li>
                            @endif
                            @if (Auth::user()->role==='admin')
                                <li class="nav-item {{ Request::segment(1) === 'pekerjaan' ? 'active' : null }}">
                                    <a class="nav-link" href="{{url('/pekerjaan')  }}">
                                        Pekerjaan
                                    </a>
                                </li>
                                <li class="nav-item dropdown {{ Request::segment(1) === 'pegawai' ? 'active' : null }} {{ Request::segment(1) === 'jabatan' ? 'active' : null }} {{ Request::segment(1) === 'kelompok_pegawai' ? 'active' : null }}">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Pegawai <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" class="logout" href="{{url('/pegawai')  }}">
                                           Daftar Pegawai
                                        </a>
                                        <a class="dropdown-item" class="logout" href="{{url('/jabatan')  }}">
                                            Jabatan
                                        </a>
                                        <a class="dropdown-item" class="logout" href="{{url('/kelompok_pegawai')  }}">
                                            Kelompok Pegawai
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item {{ Request::segment(1) === 'jam-kerja' ? 'active' : null }}">
                                    <a class="nav-link" href="{{ url('/jam-kerja') }}">
                                        Jadwal Kerja
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->role==='manajer'||Auth::user()->role==='admin')
                                <li class="nav-item"><a class="nav-link {{ Request::segment(1) === 'presensi-proyek' ? 'active' : null }}" href="{{ url('/presensi-proyek') }}">Presensi Proyek</a></li>
                            @endif
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i>Login</a>
                            </li>
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
                    @yield('content')
            </div>
        </main>

    {{-- Modal --}}
    <div class="modal fade" id="modalMd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalMdTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modalError"></div>
                    <div id="modalMdContent"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<!-- Footer -->
<footer class="page-footer font-small pt-4">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">©{{ now()->year }} Copyright:
      <a href="https://ptlundin.com/"> PT. Lundin</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->
</html>
