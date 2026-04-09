@extends('layouts.admin')

@section('title', 'Tambah Agenda')

@section('content')
  {{-- Form tambah agenda --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Tambah Agenda</h4>
      <form method="POST" action="{{ route('admin.agendas.store') }}" enctype="multipart/form-data">
        @include('admin.agendas._form', ['agenda' => new \App\Models\Agenda()])
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.agendas.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
