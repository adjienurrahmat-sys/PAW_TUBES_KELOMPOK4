<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Petak25')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background: #F7F3EC; color: #222; display: flex; flex-direction: column; min-height: 100vh; }
        main.isi { flex: 1 0 auto; }
        .tb { background: #D9D9D9; padding: 10px 0; }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-btn { font-size: 12px; letter-spacing: .08em; background: #fff; border: 1px solid #bbb; border-radius: 4px; padding: 5px 12px; color: #222; text-decoration: none; margin-left: 8px; }
        .nav-btn:hover { background: #f1f1f1; color: #222; }
        .isi { padding-top: 28px; padding-bottom: 56px; }
        .pill-title { background: #D9D9D9; border-radius: 6px; text-align: center; font-weight: 600; font-size: 18px; padding: 10px 24px; margin: 4px auto 24px; max-width: 460px; }
        .cardx { border: 1px solid #D0D0D0; border-radius: 14px; padding: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.06); background: #fff; }
        .btn-mint { background: #DDF1E8; color: #1F5A44; border: 1px solid #BFE3D3; font-weight: 600; box-shadow: 0 2px 3px rgba(0,0,0,.12); }
        .btn-mint:hover { background: #cbe9db; color: #1F5A44; }
        .btn-mint:disabled { opacity: .5; }
        .form-control { border-radius: 8px; }
        .foot { background: #D9D9D9; text-align: center; font-size: 13px; padding: 18px 12px; color: #444; }
        .foot a { color: #444; }
        /* landing */
        .hero { position: relative; min-height: 540px; background: #4a4a4a url('/img/hero.jpg') center 62%/cover; display: flex; align-items: center; padding: 32px 0; }
        .hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0,0,0,.65), rgba(0,0,0,.35)); }
        .hero-in { position: relative; display: flex; gap: 32px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        .hero .open { height: 440px; max-width: 100%; }
        .hero-cta { width: 380px; max-width: 100%; }
        .langkah { text-align: center; font-size: 14px; }
        .langkah b { display: inline-flex; width: 34px; height: 34px; border-radius: 50%; background: #DDF1E8; border: 1px solid #BFE3D3; color: #1F5A44; align-items: center; justify-content: center; margin-bottom: 8px; }
        .denah { width: 100%; height: auto; border: 1px solid #E3E3E3; border-radius: 8px; padding: 6px; background: #fff; }
        /* pilih meja */
        .lantai { font-size: 12px; letter-spacing: .12em; color: #555; margin: 18px 0 8px; font-weight: 600; }
        .grid3 { display: grid; grid-template-columns: repeat(auto-fill, minmax(112px, 1fr)); gap: 10px; }
        .pilih { position: relative; }
        .pilih input { position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; cursor: pointer; }
        .tbl { border: 1.5px solid #BFE3D3; background: #DDF1E8; border-radius: 8px; padding: 12px 4px; text-align: center; font-size: 13px; line-height: 1.3; }
        .tbl.off { background: #EDEDED; border-color: #DADADA; color: #6b6b6b; }
        .pilih input:checked + .tbl { background: #F6D9B5; border-color: #D79A52; font-weight: 600; }
        .pilih input:focus-visible + .tbl { outline: 2px solid #1F5A44; outline-offset: 2px; }
        .plan { background: #fff; border: 1px solid #D0D0D0; border-radius: 10px; padding: 16px; }
        .denah-web { display: flex; gap: 0; align-items: stretch; justify-content: center; }
        .lt { flex: 1 1 0; min-width: 0; container-type: inline-size; }
        .lt-judul { font-size: 10px; letter-spacing: .15em; color: #777; margin-bottom: 6px; }
        .rooms { display: flex; aspect-ratio: 763 / 637; border: 1.5px solid #c4c4c4; }
        .room { position: relative; border-right: 1.5px solid #c4c4c4; }
        .room:last-child { border-right: 0; }
        .room-nama { position: absolute; left: 0; right: 0; bottom: 6%; text-align: center; font-size: 10px; letter-spacing: .15em; color: #8a8a8a; pointer-events: none; }
        .dekor { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }
        .dekor * { vector-effect: non-scaling-stroke; }
        .pintu { position: absolute; border: 1.5px solid #9a9a9a; pointer-events: none; }
        .pintu-a { border-bottom: 0; border-top-right-radius: 100%; }
        .pintu-b { border-left: 0; border-bottom-right-radius: 100%; }
        .konektor { align-self: center; width: 44px; flex: none; margin: 0 -1px; }
        .dim { display: flex; align-items: center; gap: 6px; font-size: 10px; color: #888; margin-top: 10px; }
        .dim i { flex: 1; border-top: 1.5px solid #999; }
        .mk { position: absolute; z-index: 1; transform: translate(-50%, -50%); width: var(--w); height: var(--h); }
        .mk .tbl { height: 100%; display: flex; align-items: center; justify-content: center; padding: 0 2px; font-size: clamp(8px, 2.1cqw, 13px); line-height: 1.1; white-space: nowrap; overflow: hidden; border-radius: 3px; box-shadow: 2px 2px 3px rgba(0,0,0,.2); }
        .mk .tbl.off { background: #F0EDE6; border-color: #DAD5CC; }
        .mk .pilih input:checked + .tbl, .mk.pilih input:checked + .tbl { background: #FBE8CB; border-color: #E3BE80; }
        @media (max-width: 767px) { .denah-web { flex-direction: column; gap: 14px; } .konektor { display: none; } }
        .legend { display: flex; justify-content: center; gap: 14px; font-size: 12px; border: 1px dashed #bbb; border-radius: 8px; padding: 6px; margin-top: 12px; }
        .legend i { display: inline-block; width: 11px; height: 11px; border-radius: 2px; margin-right: 4px; border: 1px solid #aaa; }
        /* menu PO */
        .baris { display: flex; align-items: center; gap: 10px; border: 1px solid #D0D0D0; background: #fff; border-radius: 8px; padding: 8px 12px; margin-bottom: 8px; font-size: 14px; }
        .dot { width: 16px; height: 16px; border-radius: 50%; background: #DDF1E8; border: 1px solid #BFE3D3; flex: none; }
        .dot.habis { background: #E05555; border-color: #E05555; }
        .qty { display: flex; align-items: center; gap: 6px; }
        .qty button { width: 28px; height: 28px; border-radius: 6px; border: 1px solid #BFE3D3; background: #DDF1E8; color: #1F5A44; font-weight: 700; line-height: 1; }
        .qty button:disabled { background: #eee; border-color: #ddd; color: #999; }
        .qty span { min-width: 18px; text-align: center; }
        .desk { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 12px; color: #6b6b6b; }
        .sticky-box { position: sticky; top: 16px; max-height: calc(100vh - 32px); overflow-y: auto; }
        /* pembayaran */
        .opsi { display: flex; align-items: center; gap: 10px; border: 1px solid #D0D0D0; background: #fff; border-radius: 8px; padding: 14px; margin-bottom: 10px; cursor: pointer; }
        /* status */
        .stepper { display: flex; align-items: flex-start; justify-content: space-between; position: relative; padding: 4px 18px; }
        .stepper::before { content: ''; position: absolute; left: 12%; right: 12%; top: 17px; border-top: 2px solid #BFE3D3; }
        .step { text-align: center; font-size: 12px; position: relative; background: #fff; padding: 0 6px; }
        .step b { display: block; width: 26px; height: 26px; border-radius: 50%; margin: 0 auto 4px; background: #EEE; border: 1px solid #BFE3D3; }
        .step.on b { background: #7FC6A4; border-color: #4E9E7B; }
        .struk { border: 1px solid #D0D0D0; background: #fff; border-radius: 6px; padding: 16px; font-size: 14px; }
        @media (max-width: 767px) { .hero .open { height: 320px; } .hero { min-height: 0; } }
    </style>
</head>
<body>
<header class="tb">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="brand" href="{{ route('home') }}" aria-label="Petak25, ke beranda"><img src="{{ asset('img/logo-petak.png') }}" alt="Petak" height="20"><img src="{{ asset('img/logo-25.png') }}" alt="" height="32"></a>
        <nav>
            <a class="nav-btn" target="_blank" rel="noopener" href="https://drive.google.com/file/d/1P3_xYoj6nTv9cGhAjlxI5w7fEOZITk2j/view?usp=sharing">MENU</a>
            <a class="nav-btn" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=Petak25+Bintaro">LOCATION</a>
        </nav>
    </div>
</header>
@yield('hero')
<main class="container isi">
    @include('partials.alerts')
    @yield('content')
</main>
<footer class="foot">Petak25 · Jl. Kasuari Blok HB 2 No. 25, Bintaro Jaya Sektor 9 · IG @petak.25</footer>
@stack('scripts')
</body>
</html>
