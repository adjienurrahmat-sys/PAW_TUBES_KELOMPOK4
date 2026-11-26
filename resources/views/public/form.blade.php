@extends('layouts.pelanggan')
@section('title', 'Form Reservation')
@section('content')
<div class="pill-title">Form reservation</div>
<form method="POST" action="{{ route('pilih') }}" class="cardx mx-auto" style="max-width:600px">
    @csrf
    <label class="form-label" for="nama">Name</label>
    <input class="form-control mb-3" id="nama" name="nama" value="{{ old('nama') }}" required>
    <label class="form-label" for="jumlah_tamu">Number of guests</label>
    <input class="form-control mb-3" id="jumlah_tamu" type="number" min="1" max="20" name="jumlah_tamu" value="{{ old('jumlah_tamu', 1) }}" required>
    <div class="row">
        <div class="col-sm-6"><label class="form-label" for="tanggal">Date</label>
            <input class="form-control mb-3" id="tanggal" type="date" name="tanggal" min="{{ now()->toDateString() }}" value="{{ old('tanggal') }}" required></div>
        <div class="col-sm-6"><label class="form-label" for="jam">Reservation time</label>
            <input class="form-control mb-1" id="jam" type="time" name="jam" min="08:00" max="22:00" value="{{ old('jam', '08:00') }}" required>
            <div class="form-text mb-3">Buka setiap hari 08.00-22.00</div></div>
    </div>
    <button class="btn btn-mint w-100 py-2">Continue to select table</button>
</form>
@endsection
