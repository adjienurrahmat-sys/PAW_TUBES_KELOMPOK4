@extends('layouts.pelanggan')
@section('title', 'Menu')
@section('content')
<div class="pill-title">Menu</div>
@foreach($menus->groupBy('kategori') as $kat => $grup)
    <div class="lantai">{{ strtoupper($kat) }}</div>
    <div class="row g-2">
        @foreach($grup as $m)
            <div class="col-md-6">
                <div class="baris h-100">
                    <div class="dot {{ $m->stok > 0 ? '' : 'habis' }}"></div>
                    <div style="flex:1">{{ $m->nama }}@if($m->deskripsi)<div class="desk">{{ $m->deskripsi }}</div>@endif</div>
                    <div>{{ $m->stok > 0 ? 'Rp '.number_format($m->harga, 0, ',', '.') : 'Sold out' }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
<div class="text-center mt-4"><a class="btn btn-mint px-5" href="{{ route('form') }}">Reservation Now!</a></div>
@endsection
