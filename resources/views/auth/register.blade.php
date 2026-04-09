@extends('layouts.public')

@section('title', 'Registrasi Akun')

@section('content')
  <div class="text-center mb-4">
    @if($logoUrl)
      <img src="{{ $logoUrl }}" alt="Logo" style="height: 90px;">
    @endif
    <h4 class="mt-3">Registrasi Admin</h4>
    <p class="text-muted">Buat akun untuk mengelola display</p>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
      <label for="name">Nama</label>
      <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
    </div>
    <div class="form-group">
      <label for="username">Username</label>
      <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" class="form-control" type="password" name="password" required>
    </div>
    <div class="form-group">
      <label for="password_confirmation">Konfirmasi Password</label>
      <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
    </div>
    <div class="d-flex justify-content-between align-items-center">
      <a href="{{ route('login') }}" class="text-muted">Sudah punya akun?</a>
      <button type="submit" class="btn btn-primary">Daftar</button>
    </div>
  </form>
@endsection
