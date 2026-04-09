@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
  {{-- Form edit agenda --}}
  <div class="card">
    <div class="card-body">
      <h4 class="card-title theme-light-title">Edit Agenda</h4>
      <form method="POST" action="{{ route('admin.agendas.update', $agenda) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.agendas._form', ['agenda' => $agenda])
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.agendas.index') }}" class="btn btn-outline-light">Batal</a>
      </form>
    </div>
  </div>
@endsection
