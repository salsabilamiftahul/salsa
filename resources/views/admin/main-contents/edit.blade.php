@extends('layouts.admin')

@section('title', 'Edit Konten')

@section('content')
  {{-- Form edit konten --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Edit Konten</h4>
      <form method="POST" action="{{ route('admin.main-contents.update', $mainContent) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.main-contents._form', ['mainContent' => $mainContent])
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.main-contents.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
