@extends('layouts.admin')

@section('title', 'Edit Foto')

@section('content')
  {{-- Form edit galeri --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Edit Foto Galeri</h4>
      <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.galleries._form', ['gallery' => $gallery])
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
