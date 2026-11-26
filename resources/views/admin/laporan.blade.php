@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Laporan Penjualan</h1>
    <div class="d-flex gap-2 d-print-none">
        <a class="btn btn-mint" href="{{ route('admin.laporan.csv', request()->query()) }}">Export CSV</a>
        <button class="btn btn-dark" onclick="window.print()">Export PDF</button>
    </div>
</div>
<form method="GET" class="row g-2 mb-3 d-print-none">
    <div class="col-md-3"><label class="form-label" for="dari">Dari</label><input class="form-control" id="dari" type="date" name="dari" value="{{ request('dari') }}"></div>
    <div class="col-md-3"><label class="form-label" for="sampai">Sampai</label><input class="form-control" id="sampai" type="date" name="sampai" value="{{ request('sampai') }}"></div>
    <div class="col-md-4"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status">
        <option value="">Semua</option>
        @foreach(\App\Models\Reservasi::STATUS as $k => $l)@if($k != 'menunggu_pembayaran')<option value="{{ $k }}" @selected(request('status') == $k)>{{ $l }}</option>@endif @endforeach
    </select></div>
    <div class="col-md-2 d-flex align-items-end"><button class="btn btn-mint w-100">Terapkan</button></div>
</form>
<div class="card"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Kode</th><th>Tanggal</th><th>Nama</th><th>Meja</th><th>Metode</th><th>Status</th><th class="text-end">Total</th></tr></thead>
    <tbody>
    @forelse($data as $x)
        <tr><td>{{ $x->kode }}</td><td>{{ $x->tanggal->format('d M Y') }} {{ $x->jam }}</td><td>{{ $x->nama }}</td><td>{{ $x->meja->nomor }}</td>
            <td>{{ $x->metode_bayar }}</td><td>{{ \App\Models\Reservasi::STATUS[$x->status] }}</td><td class="text-end">Rp {{ number_format($x->total, 0, ',', '.') }}</td></tr>
    @empty <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
    <tfoot><tr><th colspan="6" class="text-end">Total (tanpa yang dibatalkan)</th><th class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</th></tr></tfoot>
</table></div></div>
@endsection
