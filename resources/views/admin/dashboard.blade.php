@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h1 class="h4 mb-3">Dashboard</h1>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card card-body"><div class="text-secondary small">Reservasi hari ini</div><div class="h3 mb-0">{{ $jumlahHariIni }}</div></div></div>
    <div class="col-md-4"><div class="card card-body"><div class="text-secondary small">Omzet hari ini</div><div class="h3 mb-0">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</div></div></div>
    <div class="col-md-4"><div class="card card-body"><div class="text-secondary small">Menunggu pembayaran</div><div class="h3 mb-0">{{ $menungguBayar }}</div></div></div>
</div>
<div class="row g-3">
    <div class="col-md-7"><div class="card card-body">
        <div class="text-secondary small mb-2">Reservasi 7 hari terakhir</div>
        @php $max = max(1, max($grafik)); @endphp
        @foreach($grafik as $tgl => $n)
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:50px" class="small">{{ $tgl }}</span>
                <div class="flex-fill bg-light rounded"><div class="rounded" style="height:14px;background:#7FC6A4;width:{{ $n / $max * 100 }}%"></div></div>
                <span class="small">{{ $n }}</span>
            </div>
        @endforeach
    </div></div>
    <div class="col-md-5"><div class="card card-body">
        <div class="text-secondary small mb-2">Stok menipis / sold out</div>
        @forelse($stokMenipis as $m)
            <div class="d-flex justify-content-between border-bottom py-1"><span>{{ $m->nama }}</span>
                <span class="badge {{ $m->stok == 0 ? 'text-bg-danger' : 'text-bg-warning' }}">{{ $m->stok == 0 ? 'Sold out' : $m->stok }}</span></div>
        @empty <span class="text-secondary">Semua stok aman.</span>
        @endforelse
    </div></div>
</div>
@endsection
