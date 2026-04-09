@extends('layouts.admin')

@section('title', 'Tambah Konten')

@section('content')
  {{-- Form tambah konten --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Tambah Konten</h4>
      <form method="POST" action="{{ route('admin.main-contents.store') }}" enctype="multipart/form-data">
        @include('admin.main-contents._form', ['mainContent' => new \App\Models\MainContent()])
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.main-contents.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
