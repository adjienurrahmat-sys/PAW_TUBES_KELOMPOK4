@extends('layouts.app')
@section('title', 'Meja')
@section('content')
<h1 class="h4 mb-1">Kelola Meja</h1>
<p class="text-secondary small">Area, X, dan Y (0-100, persen di dalam area) menentukan posisi meja pada denah saat pelanggan memilih meja. Kosongkan bila tidak ingin ditampilkan di denah.</p>
<form method="POST" action="{{ route('admin.meja.store') }}" class="card card-body mb-3">
    @csrf
    <div class="row g-2">
        <div class="col-md-1"><input class="form-control" name="nomor" placeholder="No." aria-label="Nomor" required></div>
        <div class="col-md-1"><input class="form-control" type="number" min="1" name="kapasitas" placeholder="Kursi" aria-label="Kapasitas" required></div>
        <div class="col-md-1"><input class="form-control" type="number" min="1" name="lantai" value="1" aria-label="Lantai" required></div>
        <div class="col-md-2"><select class="form-select" name="area" aria-label="Area"><option value="">Area (denah)</option><option>Ruang Tamu</option><option>Meja Makan</option><option>Teras</option><option>Galeri</option></select></div>
        <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" max="100" name="pos_x" placeholder="X (%)" aria-label="Posisi X"></div>
        <div class="col-md-1"><input class="form-control" type="number" step="0.01" min="0" max="100" name="pos_y" placeholder="Y" aria-label="Posisi Y"></div>
        <div class="col-md-2"><button class="btn btn-mint w-100">+ Tambah</button></div>
    </div>
</form>
<div class="card card-body">
@foreach($data as $m)
    <div class="row g-2 align-items-center mb-2">
        <form method="POST" action="{{ route('admin.meja.update', $m) }}" class="col-md-10 row g-2">
            @csrf @method('PUT')
            <div class="col-md-2"><input class="form-control form-control-sm" name="nomor" value="{{ $m->nomor }}" aria-label="Nomor" required></div>
            <div class="col-md-1"><input class="form-control form-control-sm" type="number" name="kapasitas" value="{{ $m->kapasitas }}" aria-label="Kapasitas" required></div>
            <div class="col-md-1"><input class="form-control form-control-sm" type="number" name="lantai" value="{{ $m->lantai }}" aria-label="Lantai" required></div>
            <div class="col-md-3"><select class="form-select form-select-sm" name="area" aria-label="Area"><option value="">-</option>@foreach(['Ruang Tamu', 'Meja Makan', 'Teras', 'Galeri'] as $a)<option @selected($m->area == $a)>{{ $a }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control form-control-sm" type="number" step="0.01" min="0" max="100" name="pos_x" value="{{ $m->pos_x }}" aria-label="Posisi X"></div>
            <div class="col-md-1"><input class="form-control form-control-sm" type="number" step="0.01" min="0" max="100" name="pos_y" value="{{ $m->pos_y }}" aria-label="Posisi Y"></div>
            <div class="col-md-2"><button class="btn btn-sm btn-mint">Simpan</button></div>
        </form>
        <form method="POST" action="{{ route('admin.meja.destroy', $m) }}" class="col-md-2" onsubmit="return confirm('Hapus meja ini?')">
            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
        </form>
    </div>
@endforeach
</div>
@endsection
