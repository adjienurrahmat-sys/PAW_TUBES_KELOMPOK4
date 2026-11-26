@extends('layouts.app')
@section('title', 'Login Staf')
@section('content')
<div class="mx-auto" style="max-width:380px">
    <h1 class="h4 mb-3">Login Pemilik / Staf</h1>
    <form method="POST" action="{{ route('login') }}" class="card card-body">
        @csrf
        <label class="form-label" for="email">Email</label>
        <input class="form-control mb-3" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label class="form-label" for="password">Password</label>
        <input class="form-control mb-3" id="password" type="password" name="password" required>
        <button class="btn btn-mint">Masuk</button>
    </form>
</div>
@endsection
