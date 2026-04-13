@extends('layouts.admin')

@section('title', 'Tambah Admin')

@section('content')
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Tambah Admin</h4>
      <form method="POST" action="{{ route('admin.admins.store') }}">
        @include('admin.admins._form', ['admin' => new \App\Models\User()])
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
