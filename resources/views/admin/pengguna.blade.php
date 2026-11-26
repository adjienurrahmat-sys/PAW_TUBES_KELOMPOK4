@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
<h1 class="h4 mb-3">Kelola Akun Staf</h1>
<form method="POST" action="{{ route('admin.pengguna.store') }}" class="card card-body mb-3">
    @csrf
    <div class="row g-2">
        <div class="col-md-3"><input class="form-control" name="name" placeholder="Nama" aria-label="Nama" required></div>
        <div class="col-md-3"><input class="form-control" type="email" name="email" placeholder="Email" aria-label="Email" required></div>
        <div class="col-md-2"><input class="form-control" value="Staf" disabled aria-label="Peran"></div>
        <div class="col-md-2"><input class="form-control" type="password" name="password" placeholder="Password" aria-label="Password" required></div>
        <div class="col-md-2"><button class="btn btn-mint w-100">+ Tambah</button></div>
    </div>
</form>
<div class="card card-body">
@foreach($data as $u)
    <div class="row g-2 align-items-center mb-2">
        <form method="POST" action="{{ route('admin.pengguna.update', $u) }}" class="col-md-10 row g-2">
            @csrf @method('PUT')
            <div class="col-md-3"><input class="form-control form-control-sm" name="name" value="{{ $u->name }}" aria-label="Nama" required></div>
            <div class="col-md-3"><input class="form-control form-control-sm" type="email" name="email" value="{{ $u->email }}" aria-label="Email" required></div>
            <div class="col-md-2"><span class="badge text-bg-{{ $u->role == 'pemilik' ? 'dark' : 'secondary' }}">{{ ucfirst($u->role) }}</span></div>
            <div class="col-md-2"><input class="form-control form-control-sm" type="password" name="password" placeholder="Password baru" aria-label="Password baru"></div>
            <div class="col-md-2"><button class="btn btn-sm btn-mint">Simpan</button></div>
        </form>
        <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" class="col-md-2" onsubmit="return confirm('Hapus pengguna ini?')">
            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
        </form>
    </div>
@endforeach
</div>
@endsection
