@extends('layouts.app')
@section('title', 'Reservasi')
@section('content')
<h1 class="h4 mb-3">Reservasi & Pesanan</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari kode / nama" aria-label="Cari"></div>
    <div class="col-md-3"><select class="form-select" name="status" aria-label="Status">
        <option value="">Semua status</option>
        @foreach(\App\Models\Reservasi::STATUS as $k => $l)<option value="{{ $k }}" @selected(request('status') == $k)>{{ $l }}</option>@endforeach
    </select></div>
    <div class="col-md-2"><input class="form-control" type="date" name="tanggal" value="{{ request('tanggal') }}" aria-label="Tanggal"></div>
    <div class="col-md-2"><select class="form-select" name="sort" aria-label="Urutkan">
        <option value="tanggal" @selected(request('sort') == 'tanggal')>Urut: Tanggal</option>
        <option value="total" @selected(request('sort') == 'total')>Urut: Total</option>
    </select></div>
    <div class="col-md-2"><button class="btn btn-mint w-100">Terapkan</button></div>
</form>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>Kode</th><th>Pelanggan</th><th>Jadwal</th><th>Meja</th><th>Pesanan</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
    @forelse($data as $x)
        <tr>
            <td>{{ $x->kode }}</td><td>{{ $x->nama }}<div class="small text-secondary">{{ $x->jumlah_tamu }} tamu @if($x->sumber === 'staf')<span class="badge text-bg-secondary">Input Staf</span>@endif</div></td>
            <td>{{ $x->tanggal->format('d M Y') }} {{ $x->jam }}</td><td>{{ $x->meja->nomor }}</td>
            <td class="small">{{ $x->items->map(fn($i) => $i->menu->nama.' ×'.$i->jumlah)->implode(', ') }}@if($x->catatan)<div class="text-secondary">Catatan: {{ $x->catatan }}</div>@endif</td>
            <td>Rp {{ number_format($x->total, 0, ',', '.') }}</td>
            <td><form method="POST" action="{{ route('admin.reservasi.status', $x) }}" class="d-flex gap-1">
                @csrf @method('PATCH')
                <select class="form-select form-select-sm" name="status" aria-label="Status {{ $x->kode }}">
                    @foreach(\App\Models\Reservasi::STATUS as $k => $l)<option value="{{ $k }}" @selected($x->status == $k)>{{ $l }}</option>@endforeach
                </select>
                <button class="btn btn-sm btn-mint">Ubah</button>
            </form></td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
