@extends('layouts.admin')

@section('title', 'Agenda')

@section('content')
  {{-- Header + action --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Agenda</h3>
    <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary">Tambah Agenda</a>
  </div>

  {{-- Pencarian + filter --}}
  <form method="GET" class="mb-3">
    <div class="form-row align-items-center mb-3">
      <div class="col-12 col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari nama agenda..." value="{{ $search }}">
      </div>
      <div class="col-12 col-md-auto mt-2 mt-md-0">
        <button type="submit" class="btn btn-primary btn-icon-search" aria-label="Cari">
          <i class="mdi mdi-magnify"></i>
        </button>
        @if($search || $filterDay || $filterDate || $filterYear)
          <a href="{{ route('admin.agendas.index') }}" class="btn btn-outline-light ml-2">Reset</a>
        @endif
      </div>
    </div>

  <div class="card">
    <div class="card-body">
      {{-- Filter hari/tanggal/tahun --}}
      <div class="d-flex flex-column flex-lg-row justify-content-lg-end align-items-lg-center mb-3">
        <div class="d-flex align-items-center text-muted mb-2 mb-lg-0 mr-lg-2">
          <i class="mdi mdi-filter-variant mr-1"></i>
          <span>Filter</span>
        </div>
        <div class="d-flex flex-row flex-nowrap align-items-center w-100 w-lg-auto overflow-auto" style="gap: .5rem;">
          <select name="day" class="custom-select custom-select-sm w-auto filter-select" onchange="this.form.submit()">
            <option value="">Semua Hari</option>
            <option value="1" {{ $filterDay == '1' ? 'selected' : '' }}>Senin</option>
            <option value="2" {{ $filterDay == '2' ? 'selected' : '' }}>Selasa</option>
            <option value="3" {{ $filterDay == '3' ? 'selected' : '' }}>Rabu</option>
            <option value="4" {{ $filterDay == '4' ? 'selected' : '' }}>Kamis</option>
            <option value="5" {{ $filterDay == '5' ? 'selected' : '' }}>Jumat</option>
            <option value="6" {{ $filterDay == '6' ? 'selected' : '' }}>Sabtu</option>
            <option value="7" {{ $filterDay == '7' ? 'selected' : '' }}>Minggu</option>
          </select>
          <input type="date" name="date" class="form-control form-control-sm w-auto" value="{{ $filterDate }}" onchange="this.form.submit()">
          <input type="number" min="2000" max="2100" name="year" class="form-control form-control-sm w-auto" placeholder="Tahun" value="{{ $filterYear }}" onchange="this.form.submit()">
          <a href="{{ route('admin.agendas.index') }}" class="btn btn-outline-light btn-sm">Reset</a>
        </div>
      </div>

      {{-- Tabel agenda --}}
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="col-title">Nama</th>
              <th class="col-schedule">Tanggal</th>
              <th>Jam</th>
              <th>Status</th>
              <th class="col-actions">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($agendas as $agenda)
              <tr>
                @php
                  $agendaDate = $agenda->starts_at
                    ? \Carbon\Carbon::parse($agenda->starts_at)->locale('id')->isoFormat('dddd, D MMM YYYY')
                    : '-';
                  $agendaTime = $agenda->starts_at
                    ? \Carbon\Carbon::parse($agenda->starts_at)->format('H:i')
                    : '-';
                  $agendaTimeEnd = $agenda->ends_at
                    ? \Carbon\Carbon::parse($agenda->ends_at)->format('H:i')
                    : null;
                  $agendaTimeLabel = $agendaTimeEnd ? ($agendaTime . ' - ' . $agendaTimeEnd) : $agendaTime;
                  $now = now();
                  $statusLabel = 'Berlangsung';
                  $statusClass = 'text-success';

                  if ($agenda->ends_at && $agenda->ends_at->lt($now)) {
                    $statusLabel = 'Selesai';
                    $statusClass = 'text-muted';
                  } elseif ($agenda->starts_at && $agenda->starts_at->gt($now)) {
                    $statusLabel = 'Terjadwal';
                    $statusClass = 'text-warning';
                  }
                @endphp
                <td class="col-title">{{ $agenda->title }}</td>
                <td class="col-schedule">{{ $agendaDate }}</td>
                <td>{{ $agendaTimeLabel }}</td>
                <td>
                  <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="col-actions d-flex">
                  <a href="{{ route('admin.agendas.edit', $agenda) }}" class="btn btn-sm btn-outline-light btn-icon-action mr-2" aria-label="Edit" title="Edit">
                    <i class="mdi mdi-pencil"></i>
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-icon-action" data-delete-action="{{ route('admin.agendas.destroy', $agenda) }}" aria-label="Hapus" title="Hapus">
                    <i class="mdi mdi-delete"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-muted">Belum ada agenda.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $agendas->links() }}
      </div>
    </div>
  </div>
  </form>
@endsection
