@extends('layouts.admin')

@section('title', 'Konten')

@section('content')
  {{-- Header + action --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Konten</h3>
    <a href="{{ route('admin.main-contents.create') }}" class="btn btn-primary">Tambah Konten</a>
  </div>

  {{-- Pencarian + filter --}}
  <form method="GET" class="mb-3">
    <div class="form-row align-items-center mb-3">
      <div class="col-12 col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari judul konten..." value="{{ $search }}">
      </div>
      <div class="col-12 col-md-auto mt-2 mt-md-0">
        <button type="submit" class="btn btn-primary btn-icon-search" aria-label="Cari">
          <i class="mdi mdi-magnify"></i>
        </button>
        @if($search || $category || $status || $media || $schedule)
          <a href="{{ route('admin.main-contents.index') }}" class="btn btn-outline-light ml-2">Reset</a>
        @endif
      </div>
    </div>

    {{-- Filter kategori, status, media, jadwal --}}
    <div class="d-flex flex-column flex-lg-row justify-content-lg-end align-items-lg-center mb-3">
      <div class="d-flex align-items-center text-muted mb-2 mb-lg-0 mr-lg-2">
        <i class="mdi mdi-filter-variant mr-1"></i>
        <span>Filter</span>
      </div>
      <div class="d-flex flex-row flex-nowrap align-items-center w-100 w-lg-auto overflow-auto" style="gap: .5rem;">
        <select name="category" class="custom-select custom-select-sm w-auto filter-select" onchange="this.form.submit()">
          <option value="">Semua Kategori</option>
          <option value="kegiatan" {{ $category === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
          <option value="spp" {{ $category === 'spp' ? 'selected' : '' }}>SPP</option>
          <option value="sop" {{ $category === 'sop' ? 'selected' : '' }}>SOP</option>
          <option value="video" {{ $category === 'video' ? 'selected' : '' }}>Video</option>
          <option value="lain-lain" {{ $category === 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
        </select>
        <select name="status" class="custom-select custom-select-sm w-auto filter-select" onchange="this.form.submit()">
          <option value="">Semua Status</option>
          <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
          <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <select name="media" class="custom-select custom-select-sm w-auto filter-select" onchange="this.form.submit()">
          <option value="">Semua Media</option>
          <option value="image" {{ $media === 'image' ? 'selected' : '' }}>Gambar</option>
          <option value="video" {{ $media === 'video' ? 'selected' : '' }}>Video</option>
        </select>
        <select name="schedule" class="custom-select custom-select-sm w-auto filter-select" onchange="this.form.submit()">
          <option value="">Semua Jadwal</option>
          <option value="ongoing" {{ $schedule === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
          <option value="upcoming" {{ $schedule === 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
          <option value="ended" {{ $schedule === 'ended' ? 'selected' : '' }}>Sudah Berakhir</option>
          <option value="unscheduled" {{ $schedule === 'unscheduled' ? 'selected' : '' }}>Tanpa Jadwal</option>
        </select>
        <a href="{{ route('admin.main-contents.index') }}" class="btn btn-outline-light btn-sm">Reset</a>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-body">

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="col-title-narrow">Judul</th>
                <th>Kategori</th>
                <th>Media</th>
                <th class="col-schedule">Jadwal</th>
                <th class="col-status">Status</th>
                <th class="col-actions">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($contents as $content)
                <tr>
                  <td class="col-title-narrow">{{ $content->title }}</td>
                  @php
                    $categoryLabels = [
                      'kegiatan' => 'Kegiatan',
                      'spp' => 'SPP',
                      'sop' => 'SOP',
                      'video' => 'Video',
                      'lain-lain' => 'Lain-lain',
                    ];
                    $categoryKey = $content->category ?? 'lain-lain';
                  @endphp
                  <td>{{ $categoryLabels[$categoryKey] ?? 'Lain-lain' }}</td>
                  <td>
                    @if(($content->media_type ?? 'image') === 'video')
                      <span class="badge badge-info">Video</span>
                    @else
                      <img src="{{ $content->image_path ? asset('storage/' . $content->image_path) : asset('corona-free-dark-bootstrap/template/assets/images/dashboard/Rectangle.jpg') }}"
                           alt="Konten" class="admin-thumb">
                    @endif
                  </td>
                  <td class="col-schedule">
                    {{ optional($content->starts_at)->format('d M Y H:i') }} -
                    {{ optional($content->ends_at)->format('d M Y H:i') }}
                  </td>
                  <td class="col-status">
                    <form method="POST" action="{{ route('admin.main-contents.toggle-status', $content) }}" class="admin-status-form">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="is_active" value="0">
                      <div class="custom-control custom-switch admin-status-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="main_content_status_{{ $content->id }}"
                          name="is_active"
                          value="1"
                          onchange="this.form.submit()"
                          {{ $content->is_active ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="main_content_status_{{ $content->id }}">
                          {{ $content->is_active ? 'Aktif' : 'Nonaktif' }}
                        </label>
                      </div>
                    </form>
                  </td>
                  <td class="col-actions d-flex">
                    <a href="{{ route('admin.main-contents.edit', $content) }}" class="btn btn-sm btn-outline-light btn-icon-action mr-2" aria-label="Edit" title="Edit">
                      <i class="mdi mdi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-icon-action" data-delete-action="{{ route('admin.main-contents.destroy', $content) }}" aria-label="Hapus" title="Hapus">
                      <i class="mdi mdi-delete"></i>
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-muted">Belum ada konten.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $contents->links() }}
        </div>
    </div>
  </div>
@endsection
