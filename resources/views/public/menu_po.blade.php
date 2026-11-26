@extends('layouts.pelanggan')
@section('title', 'Create Menu (PO)')
@section('content')
<div class="pill-title">Create Menu (PO)</div>
<form method="POST" action="{{ route('reservasi.store') }}">
    @csrf
    @foreach($d as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
    <div class="row g-4">
        <div class="col-lg-8">
            <label class="visually-hidden" for="cari">Cari menu</label>
            <input class="form-control mb-3" id="cari" type="search" placeholder="Cari menu..." oninput="cariMenu(this.value)">
            @foreach($menus->groupBy('kategori') as $kat => $grup)
                <section class="grup-menu">
                    <div class="lantai">{{ strtoupper($kat) }}</div>
                    @foreach($grup as $m)
                        @php $q = (int) ($qty[$m->id] ?? 0); @endphp
                        <div class="baris" data-nama="{{ strtolower($m->nama) }}">
                            <div class="dot {{ $m->stok > 0 ? '' : 'habis' }}"></div>
                            <div style="flex:1">{{ $m->nama }}<br><small class="text-secondary">Rp {{ number_format($m->harga, 0, ',', '.') }}{{ $m->stok > 0 ? '' : ' · sold out' }}</small>@if($m->deskripsi)<div class="desk">{{ $m->deskripsi }}</div>@endif</div>
                            <div class="qty">
                                <button type="button" onclick="ubah({{ $m->id }}, -1, {{ $m->stok }})" aria-label="Kurangi {{ $m->nama }}" @disabled($m->stok < 1)>&minus;</button>
                                <span id="v{{ $m->id }}">{{ $q }}</span>
                                <button type="button" onclick="ubah({{ $m->id }}, 1, {{ $m->stok }})" aria-label="Tambah {{ $m->nama }}" @disabled($m->stok < 1)>+</button>
                                <input type="hidden" id="q{{ $m->id }}" name="items[{{ $m->id }}]" value="{{ $q }}" data-h="{{ $m->harga }}" data-nama="{{ $m->nama }}">
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach
        </div>
        <div class="col-lg-4">
            <div class="cardx sticky-box">
                <div class="fw-semibold mb-1">Ringkasan reservasi</div>
                <p class="small text-secondary mb-3">Meja {{ $meja->nomor }} · {{ $d['jumlah_tamu'] }} tamu<br>{{ $d['tanggal'] }} · {{ $d['jam'] }}</p>
                <div class="d-flex gap-3 small mb-3">
                    <span><span class="dot habis d-inline-block align-middle"></span> sold out</span>
                    <span><span class="dot d-inline-block align-middle"></span> available</span>
                </div>
                <div class="small mb-3">
                    <div class="text-secondary mb-1">Pesanan:</div>
                    <div id="ringkas"></div>
                    <div id="kosong" class="text-secondary">Belum ada menu dipilih.</div>
                </div>
                <label class="form-label small" for="catatan">Note:</label>
                <input class="form-control mb-3" id="catatan" name="catatan" maxlength="255" value="{{ $catatan }}">
                <div class="d-flex justify-content-between fw-bold mb-3"><span>Total</span><span id="tot">Rp 0</span></div>
                <button class="btn btn-mint w-100 py-2">Payment</button>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
function ubah(id, d, max) {
    var i = document.getElementById('q' + id);
    var v = Math.max(0, Math.min(max, (parseInt(i.value) || 0) + d));
    i.value = v;
    document.getElementById('v' + id).textContent = v;
    hitung();
}
function hitung() {
    var t = 0, n = 0, box = document.getElementById('ringkas');
    box.textContent = '';
    document.querySelectorAll('[data-h]').forEach(function (e) {
        var q = parseInt(e.value) || 0;
        if (q > 0) {
            var sub = e.dataset.h * q;
            t += sub; n++;
            var row = document.createElement('div');
            row.className = 'd-flex justify-content-between gap-2 border-bottom py-1';
            var nama = document.createElement('span');
            nama.textContent = e.dataset.nama + ' \u00d7' + q;
            var harga = document.createElement('span');
            harga.className = 'text-nowrap';
            harga.textContent = 'Rp ' + sub.toLocaleString('id-ID');
            row.appendChild(nama);
            row.appendChild(harga);
            box.appendChild(row);
        }
    });
    document.getElementById('kosong').style.display = n > 0 ? 'none' : '';
    document.getElementById('tot').textContent = 'Rp ' + t.toLocaleString('id-ID');
}
function cariMenu(k) {
    k = k.toLowerCase();
    document.querySelectorAll('.grup-menu').forEach(function (g) {
        var ada = false;
        g.querySelectorAll('.baris').forEach(function (b) {
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
