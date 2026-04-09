@extends('layouts.public')

@section('title', 'Login Admin')

@section('content')
  <div class="text-center mb-4">
    @if($logoUrl)
      <img src="{{ $logoUrl }}" alt="Logo" style="height: 90px;">
    @endif
    <h4 class="mt-3">Login Admin</h4>
    <p class="text-muted">Masuk untuk mengelola konten display</p>
  </div>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
      <label for="username">Username</label>
      <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}" required autofocus>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" class="form-control" type="password" name="password" required>
    </div>
    <div class="form-group form-check">
      <input id="remember_me" class="form-check-input" type="checkbox" name="remember">
      <label for="remember_me" class="form-check-label">Ingat saya</label>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      <button type="submit" class="btn btn-primary px-5 py-2">Login</button>
    </div>
  </form>
@endsection
