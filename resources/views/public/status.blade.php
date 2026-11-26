@extends('layouts.pelanggan')
@section('title', 'Order Status')
@section('content')
@php
    $judul = ['menunggu_pembayaran' => 'WAITING FOR PAYMENT', 'diterima' => 'ORDER RECEIVED', 'diproses' => 'YOUR ORDER IS BEING PREPARED',
        'siap' => 'YOUR ORDER IS READY', 'selesai' => 'ORDER COMPLETED', 'dibatalkan' => 'ORDER CANCELLED'][$r->status];
    $langkah = ['diterima' => 'Received', 'diproses' => 'Preparing', 'siap' => 'Ready'];
    $urut = array_keys($langkah);
    $found = array_search($r->status, $urut);
    $pos = $r->status === 'selesai' ? 3 : ($found === false ? -1 : $found);
@endphp
<div class="pill-title">{{ $judul }}</div>
<div class="row g-4 justify-content-center">
    <div class="col-lg-6">
        @if($r->status === 'menunggu_pembayaran')
            <div class="alert alert-warning small">Belum dibayar. <a href="{{ route('bayar', $r->kode) }}">Bayar sekarang</a></div>
        @elseif($r->status === 'dibatalkan')
            <div class="alert alert-danger small">Reservasi ini dibatalkan.</div>
        @else
            <div class="cardx mb-3"><div class="stepper">
                @foreach($langkah as $k => $label)
                    <div class="step {{ $pos >= array_search($k, $urut) ? 'on' : '' }}"><b></b>{{ $label }}</div>
                @endforeach
            </div></div>
        @endif
        <div class="cardx text-center">
            <div class="small text-secondary">Reservation code</div>
            <div class="fs-3 fw-bold" style="font-family:monospace;letter-spacing:.1em">{{ $r->kode }}</div>
            <div>Meja {{ $r->meja->nomor }} (Lantai {{ $r->meja->lantai }}) · {{ $r->tanggal->format('d M Y') }} {{ $r->jam }}</div>
            <div class="small text-secondary mt-1">Tunjukkan kode ini saat tiba di Petak25</div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="struk">
            <div class="text-center fw-bold mb-2">Receipt</div>
            @foreach($r->items as $i)
                <div class="d-flex justify-content-between"><span>{{ $i->menu->nama }} ×{{ $i->jumlah }}</span><span>Rp {{ number_format($i->subtotal, 0, ',', '.') }}</span></div>
            @endforeach
            @if($r->catatan)<div class="text-secondary mt-1">Note: {{ $r->catatan }}</div>@endif
            <hr class="my-2" style="border-top:1px dashed #999">
            <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>Rp {{ number_format($r->total, 0, ',', '.') }}</span></div>
        </div>
    </div>
</div>
@endsection
