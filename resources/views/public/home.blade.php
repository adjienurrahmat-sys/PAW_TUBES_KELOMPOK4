@extends('layouts.pelanggan')
@section('title', 'Petak25')
@section('hero')
<section class="hero">
    <div class="container hero-in">
        <img class="open" src="{{ asset('img/open-everyday.png') }}" alt="Open Everyday, 08.00 sampai 22.00. Jl. Kasuari Blok HB 2 No. 25, Bintaro Jaya Sektor 9. Instagram @petak.25">
        <div class="hero-cta cardx">
            <h1 class="h5 mb-1">Reservasi meja &amp; pre-order</h1>
            <p class="small text-secondary">Pesan menu dan amankan tempat duduk sebelum datang, tanpa antre di kasir.</p>
            <a class="btn btn-mint w-100 py-2 mb-3" href="{{ route('form') }}">Reservation Now!</a>
            <form method="GET" action="{{ route('cek') }}">
                <label class="form-label small" for="kode">Sudah punya kode reservasi?</label>
                <div class="input-group">
                    <input class="form-control" id="kode" name="kode" placeholder="RSV-XXXXX" required>
                    <button class="btn btn-outline-dark">Cek status</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
@section('content')
<h2 class="h5 text-center mb-4">Cara reservasi</h2>
<div class="row g-3">
    @foreach(['Isi form reservasi' => 'Nama, jumlah tamu, tanggal, dan jam.', 'Pilih meja' => 'Hanya meja kosong yang bisa dipilih.', 'Pre-order menu' => 'Pesan menu sebelum datang.', 'Bayar & datang' => 'Tunjukkan kode reservasi saat tiba.'] as $judul => $isi)
        <div class="col-6 col-lg-3"><div class="cardx langkah h-100"><b>{{ $loop->iteration }}</b><div class="fw-semibold">{{ $judul }}</div><div class="small text-secondary">{{ $isi }}</div></div></div>
    @endforeach
</div>
@endsection
