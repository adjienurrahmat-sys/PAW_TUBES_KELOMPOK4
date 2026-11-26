@extends('layouts.pelanggan')
@section('title', 'Select Table')
@section('content')
@php
    $denah = [
        1 => [['nama' => 'RUANG TAMU', 'area' => 'Ruang Tamu', 'lebar' => 273, 'dekor' => 'tamu'], ['nama' => 'GALERI', 'area' => 'Galeri', 'lebar' => 139, 'dekor' => 'galeri']],
        2 => [['nama' => 'MEJA MAKAN', 'area' => 'Meja Makan', 'lebar' => 276, 'dekor' => ''], ['nama' => 'TERAS', 'area' => 'Teras', 'lebar' => 138, 'dekor' => 'teras']],
    ];
    $areaAda = collect($denah)->flatten(1)->pluck('area')->all();
    $diDenah = $semua->filter(fn ($m) => $m->pos_x !== null && $m->pos_y !== null && in_array($m->area, $areaAda, true));
    $tanpa = $semua->diff($diDenah);
@endphp
<div class="pill-title">Create Table</div>
<p class="text-secondary text-center mb-3">{{ $d['nama'] }} · {{ $d['jumlah_tamu'] }} tamu · {{ $d['tanggal'] }} {{ $d['jam'] }}</p>
<form method="POST" action="{{ route('pilih.menu') }}" class="mx-auto" style="max-width:980px">
    @csrf
    @foreach($d as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach

    <div class="plan">
        <div class="denah-web">
            @foreach($denah as $lantai => $ruang)
                <div class="lt">
                    <div class="lt-judul">LANTAI {{ $lantai }}</div>
                    <div class="rooms">
                        @foreach($ruang as $r)
                            <div class="room" style="flex: {{ $r['lebar'] }} 1 0">
                                @if($r['dekor'] === 'galeri')
                                    <svg class="dekor" viewBox="0 0 139 344" preserveAspectRatio="none" aria-hidden="true" fill="none" stroke="#b5b5b5" stroke-width="1.5">
                                        @foreach([71, 99, 128, 158, 188, 217, 246] as $y)<line x1="23" y1="{{ $y }}" x2="116" y2="{{ $y }}"/>@endforeach
                                        <line x1="70" y1="281" x2="70" y2="77"/><polyline points="62,88 70,77 78,88"/>
                                    </svg>
                                @elseif($r['dekor'] === 'teras')
                                    <svg class="dekor" viewBox="0 0 138 344" preserveAspectRatio="none" aria-hidden="true" fill="none" stroke="#8a8a8a" stroke-width="1.5">
                                        <line x1="55" y1="22" x2="138" y2="2"/><line x1="0" y1="127" x2="125" y2="110"/>
                                        <line x1="10" y1="180" x2="125" y2="164"/><line x1="5" y1="309" x2="125" y2="277"/>
                                    </svg>
                                    <div class="pintu pintu-b" style="left:0; top:36.9%; width:37.7%; height:15.1%"></div>
                                @elseif($r['dekor'] === 'tamu')
                                    <div class="pintu pintu-a" style="left:36.6%; top:81.7%; width:23.1%; height:18.3%"></div>
                                @endif
                                @foreach($diDenah->where('area', $r['area']) as $m)
                                    @php $ok = in_array($m->id, $bisa, true); $w = $m->lebar ?? 22; $h = $m->tinggi ?? 16; @endphp
                                    <label class="pilih mk" style="--w: {{ $w + 0 }}%; --h: {{ $h + 0 }}%; left: {{ $m->pos_x + 0 }}%; top: {{ $m->pos_y + 0 }}%" title="Table {{ $m->nomor }} · {{ $m->kapasitas }} kursi">
                                        <input type="radio" name="meja_id" value="{{ $m->id }}" data-label="Table {{ $m->nomor }}"
                                               aria-label="Meja {{ $m->nomor }}, {{ $m->kapasitas }} kursi, {{ $r['nama'] }}{{ $ok ? '' : ', tidak tersedia' }}"
                                               @disabled(!$ok) @checked(old('meja_id') == $m->id) required>
                                        <div class="tbl {{ $ok ? '' : 'off' }}">Table {{ $m->nomor }}</div>
                                    </label>
                                @endforeach
                                <span class="room-nama">{{ $r['nama'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if($lantai == 1)<div class="dim" aria-hidden="true"><span>&larr;</span><i></i><span>9600</span><i></i><span>&rarr;</span></div>@endif
                </div>
                @if($lantai == 1)
                    <svg class="konektor" viewBox="0 0 44 20" aria-hidden="true"><path d="M0 10 H14 L18 3 L24 17 L28 10 H44" fill="none" stroke="#9a9a9a" stroke-width="1.5"/></svg>
                @endif
            @endforeach
        </div>
    </div>

    <div class="legend mx-auto" style="max-width:420px">
        <span><i style="background:#DDF1E8"></i>Kosong</span>
        <span><i style="background:#F0EDE6"></i>Terisi</span>
        <span><i style="background:#FBE8CB"></i>Dipilih</span>
    </div>

    @if($tanpa->isNotEmpty())
        <div class="lantai">MEJA LAINNYA</div>
        <div class="grid3">
            @foreach($tanpa as $m)
                @php $ok = in_array($m->id, $bisa, true); @endphp
                <label class="pilih">
                    <input type="radio" name="meja_id" value="{{ $m->id }}" data-label="Table {{ $m->nomor }}" @disabled(!$ok) @checked(old('meja_id') == $m->id) required>
                    <div class="tbl {{ $ok ? '' : 'off' }}">Meja {{ $m->nomor }}<br><small>{{ $m->kapasitas }} kursi · Lantai {{ $m->lantai }}</small></div>
                </label>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-4">
        <button id="lanjut" class="btn btn-mint px-5 py-2" style="min-width:260px" @disabled(empty($bisa))>Select a table</button>
    </div>
    @if(empty($bisa))<div class="alert alert-warning mt-3 small">Tidak ada meja kosong untuk jam ini. <a href="{{ route('form') }}">Ubah jam atau tanggal</a>.</div>@endif
</form>
@endsection
@push('scripts')
<script>
document.querySelectorAll('input[name=meja_id]').forEach(function (i) {
    function perbarui() { if (i.checked) document.getElementById('lanjut').textContent = i.dataset.label + ' \u2192'; }
    i.addEventListener('change', perbarui);
    perbarui();
});
</script>
@endpush
