@extends('layouts.app')
@section('title', 'Input Reservasi')
@section('content')
<h1 class="h4 mb-1">Input Reservasi</h1>
<p class="text-secondary small">Untuk mencatat pelanggan yang datang langsung (walk-in) atau memesan lewat WhatsApp.</p>
<form method="POST" action="{{ route('admin.reservasi.simpan') }}">
    @csrf
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-body">
                <label class="form-label" for="nama">Nama pelanggan</label>
                <input class="form-control mb-3" id="nama" name="nama" value="{{ old('nama') }}" required>
                <div class="row">
                    <div class="col-6"><label class="form-label" for="jumlah_tamu">Jumlah tamu</label>
                        <input class="form-control mb-3" id="jumlah_tamu" type="number" min="1" max="20" name="jumlah_tamu" value="{{ old('jumlah_tamu', 1) }}" required></div>
                    <div class="col-6"><label class="form-label" for="jam">Jam</label>
                        <input class="form-control mb-3" id="jam" type="time" min="08:00" max="22:00" name="jam" value="{{ old('jam', '12:00') }}" required></div>
                </div>
                <label class="form-label" for="tanggal">Tanggal</label>
                <input class="form-control mb-3" id="tanggal" type="date" name="tanggal" min="{{ now()->toDateString() }}" value="{{ old('tanggal', now()->toDateString()) }}" required>
                <label class="form-label" for="meja_id">Meja</label>
                <select class="form-select mb-3" id="meja_id" name="meja_id" required>
                    <option value="">Pilih meja</option>
                    @foreach($mejas as $m)
                        <option value="{{ $m->id }}" @selected(old('meja_id') == $m->id)>Table {{ $m->nomor }} · {{ $m->kapasitas }} kursi · Lantai {{ $m->lantai }}{{ $m->area ? ' · '.$m->area : '' }}</option>
                    @endforeach
                </select>
                <div class="row">
                    <div class="col-6"><label class="form-label" for="metode_bayar">Pembayaran</label>
                        <select class="form-select mb-3" id="metode_bayar" name="metode_bayar" required>
                            @foreach(['Tunai', 'QRIS', 'Transfer Bank', 'E-wallet'] as $mb)<option @selected(old('metode_bayar') == $mb)>{{ $mb }}</option>@endforeach
                        </select></div>
                    <div class="col-6"><label class="form-label" for="status">Status awal</label>
                        <select class="form-select mb-3" id="status" name="status" required>
                            @foreach(['diterima' => 'Diterima (Received)', 'diproses' => 'Diproses (Preparing)', 'siap' => 'Siap (Ready)', 'selesai' => 'Selesai'] as $k => $l)<option value="{{ $k }}" @selected(old('status') == $k)>{{ $l }}</option>@endforeach
                        </select></div>
                </div>
                <label class="form-label" for="catatan">Catatan</label>
                <input class="form-control mb-3" id="catatan" name="catatan" maxlength="255" value="{{ old('catatan') }}">
                <div class="d-flex justify-content-between fw-bold mb-3"><span>Total</span><span id="tot">Rp 0</span></div>
                <button class="btn btn-mint w-100">Simpan reservasi</button>
            </div>
        </div>
        <div class="col-lg-7">
            <input class="form-control mb-3" type="search" placeholder="Cari menu..." aria-label="Cari menu" oninput="cariMenu(this.value)">
            <div class="card card-body" style="max-height:640px; overflow-y:auto">
                @foreach($menus->groupBy('kategori') as $kat => $grup)
                    <div class="grup-menu">
                        <div class="small fw-semibold text-secondary mt-2 mb-1">{{ strtoupper($kat) }}</div>
                        @foreach($grup as $m)
                            <div class="d-flex align-items-center gap-2 mb-1 baris-menu" data-nama="{{ strtolower($m->nama) }}">
                                <span style="flex:1">{{ $m->nama }} <small class="text-secondary">Rp {{ number_format($m->harga, 0, ',', '.') }}</small>@if($m->stok == 0) <span class="badge text-bg-danger">Sold out</span>@endif</span>
                                <input class="form-control form-control-sm" style="width:72px" type="number" min="0" max="{{ $m->stok }}" name="items[{{ $m->id }}]"
                                       value="{{ old('items.'.$m->id, 0) }}" data-h="{{ $m->harga }}" aria-label="Jumlah {{ $m->nama }}" @disabled($m->stok == 0) oninput="hitung()">
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
function hitung() {
    var t = 0;
    document.querySelectorAll('[data-h]').forEach(function (e) { t += e.dataset.h * (parseInt(e.value) || 0); });
    document.getElementById('tot').textContent = 'Rp ' + t.toLocaleString('id-ID');
}
function cariMenu(k) {
    k = k.toLowerCase();
    document.querySelectorAll('.grup-menu').forEach(function (g) {
        var ada = false;
        g.querySelectorAll('.baris-menu').forEach(function (b) {
            var cocok = b.dataset.nama.indexOf(k) !== -1;
            b.style.display = cocok ? '' : 'none';
            if (cocok) ada = true;
        });
        g.style.display = ada ? '' : 'none';
    });
}
hitung();
</script>
@endpush
