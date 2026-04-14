@extends('layouts.admin')

@section('title', 'Galeri Foto')

@section('content')
  {{-- Header + action --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Galeri Foto</h3>
    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">Tambah Foto</a>
  </div>

  {{-- Pencarian --}}
  <form method="GET" class="mb-3">
    <div class="form-row align-items-center">
      <div class="col-12 col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari judul galeri..." value="{{ $search }}">
      </div>
      <div class="col-12 col-md-auto mt-2 mt-md-0">
        <button type="submit" class="btn btn-primary btn-icon-search" aria-label="Cari">
          <i class="mdi mdi-magnify"></i>
        </button>
        @if($search)
          <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-light ml-2">Reset</a>
        @endif
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-body">
      {{-- Tabel galeri --}}
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="col-title-narrow">Judul</th>
              <th>Gambar</th>
              <th class="col-schedule">Jadwal</th>
              <th class="col-status">Status</th>
              <th class="col-actions">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($galleries as $gallery)
              <tr>
                <td class="col-title-narrow">{{ $gallery->title }}</td>
                <td>
                  <img src="{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : asset('corona-free-dark-bootstrap/template/assets/images/dashboard/img_6.jpg') }}"
                       alt="Galeri" class="admin-thumb">
                </td>
                <td class="col-schedule">
                  {{ optional($gallery->starts_at)->format('d M Y H:i') }} -
                  {{ optional($gallery->ends_at)->format('d M Y H:i') }}
                </td>
                <td class="col-status">
                  <form method="POST" action="{{ route('admin.galleries.toggle-status', $gallery) }}" class="admin-status-form">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_active" value="0">
                    <div class="custom-control custom-switch admin-status-switch">
                      <input
                        type="checkbox"
                        class="custom-control-input"
                        id="gallery_status_{{ $gallery->id }}"
                        name="is_active"
                        value="1"
                        onchange="this.form.submit()"
                        {{ $gallery->is_active ? 'checked' : '' }}
                      >
                      <label class="custom-control-label" for="gallery_status_{{ $gallery->id }}">
                        {{ $gallery->is_active ? 'Aktif' : 'Nonaktif' }}
                      </label>
                    </div>
                  </form>
                </td>
                <td class="col-actions d-flex">
                  <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-sm btn-outline-light btn-icon-action mr-2" aria-label="Edit" title="Edit">
                    <i class="mdi mdi-pencil"></i>
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-icon-action" data-delete-action="{{ route('admin.galleries.destroy', $gallery) }}" aria-label="Hapus" title="Hapus">
                    <i class="mdi mdi-delete"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-muted">Belum ada galeri.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $galleries->links() }}
      </div>
    </div>
  </div>
@endsection
