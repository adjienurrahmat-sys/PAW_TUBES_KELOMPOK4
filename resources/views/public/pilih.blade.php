@extends('layouts.app')
@section('title', 'Pilih Meja & Menu')
@section('content')
<h1 class="h4 mb-1">Pilih Meja & Menu</h1>
<p class="text-secondary">{{ $d['nama'] }} · {{ $d['jumlah_tamu'] }} tamu · {{ $d['tanggal'] }} {{ $d['jam'] }}</p>
<form method="POST" action="{{ route('reservasi.store') }}">
    @csrf
    @foreach($d as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach

    <h2 class="h6">Meja tersedia</h2>
    <div class="row g-2 mb-4">
        @forelse($mejas as $m)
            <div class="col-6 col-md-3">
                <label class="card card-body h-100">
                    <input type="radio" name="meja_id" value="{{ $m->id }}" @checked(old('meja_id') == $m->id) required>
                    <b>Meja {{ $m->nomor }}</b>
                    <span class="small text-secondary">{{ $m->kapasitas }} kursi · Lantai {{ $m->lantai }}</span>
                </label>
            </div>
        @empty
            <div class="alert alert-warning">Tidak ada meja kosong untuk jam ini. <a href="{{ route('home') }}">Ubah jam/tanggal</a>.</div>
        @endforelse
    </div>

    <h2 class="h6">Pre-order menu</h2>
    <div class="card mb-3"><table class="table mb-0 align-middle">
        <thead><tr><th>Menu</th><th>Kategori</th><th>Harga</th><th style="width:110px">Jumlah</th></tr></thead>
        <tbody>
        @foreach($menus as $m)
            <tr>
                <td>{{ $m->nama }}</td><td>{{ $m->kategori }}</td><td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                <td>
                    @if($m->stok > 0)
                        <input class="form-control form-control-sm" type="number" min="0" max="{{ $m->stok }}"
                               name="items[{{ $m->id }}]" value="{{ old('items.'.$m->id, 0) }}" aria-label="Jumlah {{ $m->nama }}">
                    @else
                        <span class="badge text-bg-danger">Sold out</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <button class="btn btn-mint" @disabled($mejas->isEmpty())>Lanjut ke pembayaran</button>
</form>
@endsection
