<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Petak25')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background: #F7F3EC; color: #2b2b2b; }
        .brand { letter-spacing: .2em; font-weight: 700; }
        .topbar { background: #D9D9D9; padding: 10px 0; }
        .btn-mint { background: #DDF1E8; color: #1F5A44; border: 1px solid #BFE3D3; font-weight: 600; }
        .btn-mint:hover { background: #cbe9db; color: #1F5A44; }
        .card { border-radius: 12px; }
    </style>
</head>
<body>
<nav class="navbar topbar navbar-expand-md">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 py-0" href="{{ route('home') }}" aria-label="Petak25, ke beranda"><img src="{{ asset('img/logo-petak.png') }}" alt="Petak" height="20"><img src="{{ asset('img/logo-25.png') }}" alt="" height="32"></a>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            @auth
                @if(auth()->user()->isPemilik())
                    <a class="btn btn-sm btn-light" href="{{ route('admin.dashboard') }}">Dashboard</a>
                @endif
                <a class="btn btn-sm btn-light" href="{{ route('admin.reservasi') }}">Reservasi</a>
                <a class="btn btn-sm btn-light" href="{{ route('admin.reservasi.baru') }}">Input Reservasi</a>
                <a class="btn btn-sm btn-light" href="{{ route('admin.menu') }}">Menu</a>
                @if(auth()->user()->isPemilik())
                    <a class="btn btn-sm btn-light" href="{{ route('admin.meja') }}">Meja</a>
                    <a class="btn btn-sm btn-light" href="{{ route('admin.laporan') }}">Laporan</a>
                    <a class="btn btn-sm btn-light" href="{{ route('admin.pengguna') }}">Pengguna</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                    <button class="btn btn-sm btn-dark">Logout ({{ auth()->user()->role }})</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
<main class="container py-4">
    @include('partials.alerts')
    @yield('content')
</main>
@stack('scripts')
</body>
</html>
