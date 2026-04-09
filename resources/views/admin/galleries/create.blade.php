@extends('layouts.admin')

@section('title', 'Tambah Foto')

@section('content')
  {{-- Form tambah galeri --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Tambah Foto Galeri</h4>
      <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
        @include('admin.galleries._form', ['gallery' => new \App\Models\GalleryItem()])
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
