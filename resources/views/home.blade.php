@extends('layouts.public')

@section('title', 'Homepage Admin')

@section('content')
  <div class="text-center">
    <div class="mb-4">
      @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" style="height: 120px;">
      @endif
    </div>
    <h3 class="mb-2">{{ $institutionName }}</h3>
    <p class="text-muted mb-4">Selamat datang di panel utama. Silakan pilih tindakan berikut.</p>
    <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
      <a href="{{ route('display') }}" class="btn btn-primary btn-lg mr-md-2 mb-2 mb-md-0">
        <i class="mdi mdi-television-classic mr-1"></i> Preview Display
      </a>
      @guest
        <a href="{{ url('/admin/login') }}" class="btn btn-outline-light btn-lg">
          <i class="mdi mdi-account-key mr-1"></i> Login Admin
        </a>
      @endguest
      @auth
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-lg">
          <i class="mdi mdi-view-dashboard mr-1"></i> Dashboard
        </a>
      @endauth
    </div>
  </div>
@endsection
