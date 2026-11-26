@extends('layouts.app')
@section('title', 'Menu')
@section('content')
@php $pemilik = auth()->user()->isPemilik(); @endphp
<h1 class="h4 mb-3">Menu & Stok</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari menu" aria-label="Cari"></div>
    <div class="col-md-3"><select class="form-select" name="status" aria-label="Ketersediaan">
        <option value="">Semua</option><option value="tersedia" @selected(request('status') == 'tersedia')>Tersedia</option><option value="menipis" @selected(request('status') == 'menipis')>Stok menipis (1-5)</option><option value="habis" @selected(request('status') == 'habis')>Sold out</option></select></div>
    <div class="col-md-3"><select class="form-select" name="sort" aria-label="Urutkan">
        @foreach(['nama' => 'Nama', 'harga' => 'Harga', 'stok' => 'Stok'] as $k => $l)<option value="{{ $k }}" @selected(request('sort') == $k)>Urut: {{ $l }}</option>@endforeach</select></div>
    <div class="col-md-2"><button class="btn btn-mint w-100">Terapkan</button></div>
</form>

@if($pemilik)
<form method="POST" action="{{ route('admin.menu.store') }}" class="card card-body mb-3">
    @csrf
    <div class="row g-2">
        <div class="col-md-4"><input class="form-control" name="nama" placeholder="Nama menu" aria-label="Nama menu" required></div>
        <div class="col-md-2"><input class="form-control" name="kategori" placeholder="Kategori" aria-label="Kategori" required></div>
        <div class="col-md-2"><input class="form-control" type="number" min="0" name="harga" placeholder="Harga" aria-label="Harga" required></div>
        <div class="col-md-2"><input class="form-control" type="number" min="0" name="stok" placeholder="Stok" aria-label="Stok" required></div>
        <div class="col-md-2"><button class="btn btn-mint w-100">+ Tambah</button></div>
    </div>
</form>
@endif

<div class="card card-body">
@foreach($data as $m)
    @if($pemilik)
    <div class="row g-2 align-items-center mb-2">
        <form method="POST" action="{{ route('admin.menu.update', $m) }}" class="col-md-9 row g-2">
            @csrf @method('PUT')
            <div class="col-md-4"><input class="form-control form-control-sm" name="nama" value="{{ $m->nama }}" aria-label="Nama" required>@if($m->stok == 0)<span class="badge text-bg-danger">Sold out</span>@elseif($m->stok <= 5)<span class="badge text-bg-warning">Menipis</span>@endif</div>
            <div class="col-md-3"><input class="form-control form-control-sm" name="kategori" value="{{ $m->kategori }}" aria-label="Kategori" required></div>
            <div class="col-md-2"><input class="form-control form-control-sm" type="number" name="harga" value="{{ $m->harga }}" aria-label="Harga" required></div>
            <div class="col-md-2"><input class="form-control form-control-sm" type="number" name="stok" value="{{ $m->stok }}" aria-label="Stok" required></div>
            <div class="col-md-1"><button class="btn btn-sm btn-mint">Simpan</button></div>
        </form>
        <div class="col-md-3 d-flex gap-1">
            <form method="POST" action="{{ route('admin.menu.soldout', $m) }}">@csrf @method('PATCH')
                <button class="btn btn-sm btn-danger" @disabled($m->stok == 0)>Sold out</button></form>
            <form method="POST" action="{{ route('admin.menu.destroy', $m) }}" onsubmit="return confirm('Hapus menu ini?')">@csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Hapus</button></form>
        </div>
    </div>
    @else
    <div class="row g-2 align-items-center mb-2">
        <div class="col-md-3">{{ $m->nama }} <span class="text-secondary small">{{ $m->kategori }}</span></div>
        <div class="col-md-2">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
        <div class="col-md-2">@if($m->stok == 0)<span class="badge text-bg-danger">Sold out</span>@elseif($m->stok <= 5)<span class="badge text-bg-warning">Menipis ({{ $m->stok }})</span>@else Stok {{ $m->stok }} @endif</div>
        <form method="POST" action="{{ route('admin.menu.stok', $m) }}" class="col-md-3 d-flex gap-1">
            @csrf @method('PATCH')
            <input class="form-control form-control-sm" type="number" min="0" name="stok" value="{{ $m->stok }}" aria-label="Stok {{ $m->nama }}">
            <button class="btn btn-sm btn-mint text-nowrap">Ubah stok</button>
        </form>
        <form method="POST" action="{{ route('admin.menu.soldout', $m) }}" class="col-md-2">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-danger w-100" @disabled($m->stok == 0)>Sold out</button>
        </form>
    </div>
    @endif
@endforeach
</div>
@endsection
