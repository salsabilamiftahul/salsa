@extends('layouts.admin')

@section('title', 'Data Admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Kelola Admin</h3>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">Tambah Admin</a>
  </div>

  <form method="GET" class="mb-3">
    <div class="form-row align-items-center">
      <div class="col-12 col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari nama atau username admin..." value="{{ $search }}">
      </div>
      <div class="col-12 col-md-auto mt-2 mt-md-0">
        <button type="submit" class="btn btn-primary btn-icon-search" aria-label="Cari">
          <i class="mdi mdi-magnify"></i>
        </button>
        @if($search !== '')
          <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-light ml-2">Reset</a>
        @endif
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Nama Admin</th>
              <th>Username</th>
              <th>Role</th>
              <th>Dibuat</th>
              <th class="col-actions">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($admins as $admin)
              <tr>
                <td>
                  <div class="font-weight-bold">{{ $admin->name }}</div>
                  @if((int) auth()->id() === (int) $admin->id)
                    <small class="text-success">Akun yang sedang digunakan saat ini</small>
                  @endif
                </td>
                <td>{{ $admin->username }}</td>
                <td>
                  @if($admin->isSuperAdmin())
                    <span class="badge badge-success">Super Admin</span>
                  @else
                    <span class="badge badge-outline-light">Admin</span>
                  @endif
                </td>
                <td>{{ optional($admin->created_at)->format('d M Y H:i') ?? '-' }}</td>
                <td class="col-actions d-flex">
                  <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-light btn-icon-action mr-2" aria-label="Edit" title="Edit">
                    <i class="mdi mdi-pencil"></i>
                  </a>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger btn-icon-action"
                    data-delete-action="{{ route('admin.admins.destroy', $admin) }}"
                    aria-label="Hapus"
                    title="Hapus"
                    @if((int) auth()->id() === (int) $admin->id || $admin->isSuperAdmin()) disabled @endif
                  >
                    <i class="mdi mdi-delete"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-muted">Belum ada data admin.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $admins->links() }}
      </div>
    </div>
  </div>
@endsection
