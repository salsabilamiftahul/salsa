@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
  {{-- Ringkasan statistik --}}
  <div class="row">
    <div class="col-md-4 grid-margin stretch-card">
      <a href="{{ route('admin.main-contents.index') }}" class="d-block w-100 text-decoration-none" aria-label="Buka Konten">
        <div class="card h-100">
          <div class="card-body">
            <div class="position-relative">
              <h5 class="text-muted mb-0 pr-5">Konten</h5>
              <div class="d-flex align-items-center justify-content-center position-absolute" style="top: -6px; right: 0; width: 56px; height: 56px;">
                <i class="mdi mdi-play-circle text-warning" style="font-size: 36px;"></i>
              </div>
            </div>
            <h3 class="mb-0 mt-1">{{ $mainContentCount }}</h3>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
      <a href="{{ route('admin.agendas.index') }}" class="d-block w-100 text-decoration-none" aria-label="Buka Agenda">
        <div class="card h-100">
          <div class="card-body">
            <div class="position-relative">
              <h5 class="text-muted mb-0 pr-5">Agenda</h5>
              <div class="d-flex align-items-center justify-content-center position-absolute" style="top: -6px; right: 0; width: 56px; height: 56px;">
                <i class="mdi mdi-calendar-clock text-primary" style="font-size: 36px;"></i>
              </div>
            </div>
            <h3 class="mb-0 mt-1">{{ $agendaCount }}</h3>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
      <a href="{{ route('admin.galleries.index') }}" class="d-block w-100 text-decoration-none" aria-label="Buka Galeri">
        <div class="card h-100">
          <div class="card-body">
            <div class="position-relative">
              <h5 class="text-muted mb-0 pr-5">Galeri</h5>
              <div class="d-flex align-items-center justify-content-center position-absolute" style="top: -6px; right: 0; width: 56px; height: 56px;">
                <i class="mdi mdi-image-multiple text-success" style="font-size: 36px;"></i>
              </div>
            </div>
            <h3 class="mb-0 mt-1">{{ $galleryCount }}</h3>
          </div>
        </div>
      </a>
    </div>
  </div>

  {{-- Tabel konten terbaru --}}
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title theme-light-title mb-3">Konten Terbaru</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Jenis</th>
                  <th class="col-title">Judul</th>
                  <th>Dibuat</th>
                  <th class="col-status">Status</th>
                  <th class="col-actions">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentContents as $content)
                  <tr>
                    <td>{{ $content['type'] }}</td>
                    <td class="col-title">{{ $content['title'] }}</td>
                    <td>{{ optional($content['created_at'])->format('d M Y H:i') }}</td>
                    <td class="col-status">
                      <span class="{{ $content['is_active'] ? 'text-success' : 'text-danger' }}">
                        {{ $content['is_active'] ? 'Aktif' : 'Nonaktif' }}
                      </span>
                    </td>
                    <td class="col-actions">
                      <a href="{{ $content['edit_url'] }}" class="btn btn-sm btn-outline-light btn-icon-action mr-2" aria-label="Edit" title="Edit">
                        <i class="mdi mdi-pencil"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger btn-icon-action" data-delete-action="{{ $content['delete_url'] }}" aria-label="Hapus" title="Hapus">
                        <i class="mdi mdi-delete"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-muted">Belum ada data.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            {{ $recentContents->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
