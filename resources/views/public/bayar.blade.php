@extends('layouts.pelanggan')
@section('title', 'Payment')
@section('content')
@php
    $qrisAsli = file_exists(public_path('img/qris.png'));
    $bank = config('petak25.bank');
    $ewallet = config('petak25.ewallet');
    $totalRp = 'Rp ' . number_format($r->total, 0, ',', '.');
@endphp
<div class="pill-title">Payment</div>
<div class="row g-4 justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="cardx small h-100">
            <div><b>{{ $r->kode }}</b> · Meja {{ $r->meja->nomor }} · {{ $r->tanggal->format('d M Y') }} {{ $r->jam }}</div>
            @foreach($r->items as $i)
                <div class="d-flex justify-content-between border-bottom py-1"><span>{{ $i->menu->nama }} ×{{ $i->jumlah }}</span><span>Rp {{ number_format($i->subtotal, 0, ',', '.') }}</span></div>
            @endforeach
            <div class="d-flex justify-content-between fw-bold pt-2 fs-6"><span>Total</span><span>{{ $totalRp }}</span></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-5">
        @if($r->status === 'menunggu_pembayaran')
        <form method="POST" action="{{ route('bayar.proses', $r->kode) }}">
            @csrf
            <label class="opsi"><input type="radio" name="metode_bayar" value="QRIS" data-panel="qris" required> <span>QRIS</span></label>
            <label class="opsi"><input type="radio" name="metode_bayar" value="Transfer Bank" data-panel="transfer"> <span>Transfer Bank</span></label>
            <label class="opsi"><input type="radio" name="metode_bayar" value="E-wallet" data-panel="ewallet"> <span>E-wallet · GoPay, OVO</span></label>

            <div id="panel-qris" class="cardx text-center mb-3" hidden>
                <div class="fw-semibold mb-2">Scan QRIS untuk membayar</div>
                @if($qrisAsli)
                    <img src="{{ asset('img/qris.png') }}" alt="QRIS Petak25" style="max-width:240px;width:100%">
                @else
                    <div id="qr-demo" data-teks="PETAK25|{{ $r->kode }}|{{ $r->total }}" style="max-width:220px;margin:0 auto" role="img" aria-label="QR code pembayaran {{ $r->kode }}"></div>
                    <div class="small text-secondary mt-2">QR contoh untuk demo</div>
                @endif
                <div class="mt-2">Jumlah: <b>{{ $totalRp }}</b></div>
                <div class="small text-secondary">Berita: {{ $r->kode }}</div>
            </div>

            <div id="panel-transfer" class="cardx mb-3" hidden>
                <div class="fw-semibold mb-2">Transfer ke rekening</div>
                <div class="small text-secondary">Bank</div><div>{{ $bank['nama'] }}</div>
                <div class="small text-secondary mt-2">Nomor rekening</div>
                <div class="d-flex align-items-center gap-2"><b id="no-rek" class="fs-5">{{ $bank['nomor'] }}</b>
                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="salin('no-rek', this)">Salin</button></div>
                <div class="small text-secondary mt-2">Atas nama</div><div>{{ $bank['atas_nama'] }}</div>
                <div class="small text-secondary mt-2">Jumlah transfer</div><div class="fw-bold">{{ $totalRp }}</div>
                <div class="small text-secondary mt-2">Berita transfer</div><div>{{ $r->kode }}</div>
            </div>

            <div id="panel-ewallet" class="cardx mb-3" hidden>
                <div class="fw-semibold mb-2">Kirim ke e-wallet</div>
                @foreach($ewallet as $nama => $nomor)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                        <span>{{ $nama }}<br><b id="no-{{ strtolower($nama) }}">{{ $nomor }}</b></span>
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="salin('no-{{ strtolower($nama) }}', this)">Salin</button>
                    </div>
                @endforeach
                <div class="small text-secondary mt-2">Jumlah: <b>{{ $totalRp }}</b> · Catatan: {{ $r->kode }}</div>
            </div>

            <p class="small text-secondary">Data pembayaran di atas adalah contoh untuk demo.</p>
            <button class="btn btn-mint w-100 py-2">Saya sudah membayar</button>
        </form>
        @else
            <a class="btn btn-mint w-100" href="{{ route('status', $r->kode) }}">Lihat status pesanan</a>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
var qrSudah = false;
function tampilPanel(nama) {
    ['qris', 'transfer', 'ewallet'].forEach(function (p) {
        var el = document.getElementById('panel-' + p);
        if (el) el.hidden = (p !== nama);
    });
    var demo = document.getElementById('qr-demo');
    if (nama === 'qris' && demo && !qrSudah && window.qrcode) {
        var qr = qrcode(0, 'M');
        qr.addData(demo.dataset.teks);
        qr.make();
        demo.innerHTML = qr.createSvgTag(5, 2);
        var svg = demo.querySelector('svg');
        if (svg) { svg.style.width = '100%'; svg.style.height = 'auto'; }
        qrSudah = true;
    }
}
document.querySelectorAll('input[name=metode_bayar]').forEach(function (i) {
    i.addEventListener('change', function () { if (i.checked) tampilPanel(i.dataset.panel); });
    if (i.checked) tampilPanel(i.dataset.panel);
});
function salin(id, tombol) {
    var teks = document.getElementById(id).textContent.trim();
    var selesai = function () { var t = tombol.textContent; tombol.textContent = 'Tersalin'; setTimeout(function () { tombol.textContent = t; }, 1500); };
    if (navigator.clipboard) { navigator.clipboard.writeText(teks).then(selesai); }
}
</script>
@endpush
