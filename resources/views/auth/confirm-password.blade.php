@extends('layouts.public')

@section('title', 'Konfirmasi Password')

@section('content')
  <div class="text-center mb-4">
    @if($logoUrl)
      <img src="{{ $logoUrl }}" alt="Logo" style="height: 90px;">
    @endif
    <h4 class="mt-3">Konfirmasi Password</h4>
    <p class="text-muted">Masukkan password untuk melanjutkan</p>
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

  <form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn btn-primary btn-block">Konfirmasi</button>
  </form>
@endsection
