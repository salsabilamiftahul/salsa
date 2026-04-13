@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Edit Admin</h4>
      <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
        @method('PUT')
        @include('admin.admins._form', ['admin' => $admin])
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
