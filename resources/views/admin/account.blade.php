@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('content')
  <div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title theme-light-title">Pengaturan Akun</h4>
          <form method="POST" action="{{ route('admin.account.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="username">Username Admin</label>
              <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $adminUsername) }}" required>
              @error('username')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="password">Password Baru</label>
              <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
              <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti password.</small>
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Akun</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
