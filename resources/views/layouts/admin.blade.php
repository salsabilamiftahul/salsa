<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin')</title>
    {{-- Vendor styles --}}
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/custom-overrides.css') }}">
    {{-- Admin overrides --}}
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    <script>
      (function () {
        var theme = localStorage.getItem('admin-theme') || 'dark';
        if (theme === 'light') document.documentElement.classList.add('theme-light');
      })();
    </script>
    @if($logoUrl)
      <link rel="shortcut icon" href="{{ $logoUrl }}">
    @else
      <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64'%3E%3Crect width='100%25' height='100%25' fill='%231b1e2b'/%3E%3Ctext x='50%25' y='50%25' fill='%23ffffff' font-family='Arial' font-size='36' font-weight='700' text-anchor='middle' dominant-baseline='central'%3EP%3C/text%3E%3C/svg%3E">
    @endif
    @stack('styles')
    <style>
      /* Light theme text tweaks */
      html.theme-light .content-wrapper .card-title,
      html.theme-light .content-wrapper h4.card-title,
      html.theme-light .content-wrapper .card-body h2,
      html.theme-light .content-wrapper .card-body h3,
      html.theme-light .content-wrapper .card-body h4,
      html.theme-light .content-wrapper .card-body h5,
      html.theme-light .content-wrapper .card-body h6 {
        color: #111827 !important;
      }

      /* Icon buttons used in admin tables */
      .btn-icon-search,
      .btn-icon-action {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 6px !important;
      }

      .btn-icon-search i,
      .btn-icon-action i {
        font-size: 22px !important;
        line-height: 1 !important;
      }

      /* Top right account dropdown */
      .navbar .mdi-account-circle {
        font-size: 30px !important;
        line-height: 1 !important;
      }

      .admin-account-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #ffffff;
        margin-left: 0.45rem;
        line-height: 1;
      }

      html.theme-light .admin-account-name {
        color: #111827;
      }

      .admin-account-link {
        display: inline-flex !important;
        align-items: center !important;
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
      }

      /* Branding */
      .admin-logo {
        height: 32px;
        width: 32px;
        object-fit: contain;
      }

      .admin-italic {
        font-style: italic;
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      {{-- Sidebar --}}
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-start fixed-top">
          <a class="sidebar-brand brand-logo" href="{{ route('admin.dashboard') }}">
            <span class="d-flex align-items-center">
              @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;" class="mr-2">
              @endif
              <span class="h6 mb-0 text-white fst-italic">System for Agenda and Library Services Access</span>
            </span>
          </a>
          <a class="sidebar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
            @else
              <span class="h5 mb-0 text-white">P</span>
            @endif
          </a>
        </div>
        <ul class="nav">
          <li class="nav-item nav-category">
            <span class="nav-link">Navigasi</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
              <span class="menu-icon"><i class="mdi mdi-home"></i></span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.preview.edit') }}">
              <span class="menu-icon"><i class="mdi mdi-television-classic"></i></span>
              <span class="menu-title">Preview Display</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.main-contents.index') }}">
              <span class="menu-icon"><i class="mdi mdi-play-circle"></i></span>
              <span class="menu-title">Konten</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.agendas.index') }}">
              <span class="menu-icon"><i class="mdi mdi-calendar-clock"></i></span>
              <span class="menu-title">Agenda</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.galleries.index') }}">
              <span class="menu-icon"><i class="mdi mdi-image-multiple"></i></span>
              <span class="menu-title">Galeri Foto</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.settings.edit') }}">
              <span class="menu-icon"><i class="mdi mdi-settings"></i></span>
              <span class="menu-title">Pengaturan</span>
            </a>
          </li>
        </ul>
      </nav>

      <div class="container-fluid page-body-wrapper">
        {{-- Top navbar --}}
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <div class="nav-link mt-0 d-none d-lg-flex">
                  <span class="text-muted">Panel Admin</span>
                </div>
              </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item d-flex align-items-center mr-2">
                <button type="button" class="btn btn-outline-light btn-sm theme-toggle-btn" id="themeToggle" aria-label="Ubah tema">
                  <i class="mdi mdi-white-balance-sunny"></i>
                </button>
              </li>
              @auth
                <li class="nav-item dropdown border-left">
                  <a class="nav-link dropdown-toggle admin-account-link" id="adminMenuDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-account-circle"></i>
                    <span class="admin-account-name d-none d-md-inline-block">
                      {{ auth()->user()->username ?? auth()->user()->name ?? 'Admin' }}
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="adminMenuDropdown">
                    <h6 class="p-3 mb-0">Akun</h6>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.account.edit') }}" class="dropdown-item preview-item">
                      <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                          <i class="mdi mdi-settings text-primary"></i>
                        </div>
                      </div>
                      <div class="preview-item-content">
                        <p class="preview-subject ellipsis mb-1 text-small">Pengaturan Akun</p>
                      </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-logout text-danger"></i>
                          </div>
                        </div>
                        <div class="preview-item-content">
                          <p class="preview-subject ellipsis mb-1 text-small">Logout</p>
                        </div>
                      </button>
                    </form>
                  </div>
                </li>
              @endauth
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>

        {{-- Main content --}}
        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
          </div>
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block fst-italic admin-italic">System for Agenda and Library Services Access (SALSA) - 2026</span>
            </div>
          </footer>
        </div>
      </div>
    </div>

    {{-- Modal konfirmasi hapus --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel">Hapus Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Yakin mau dihapus?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
            <form method="POST" id="confirmDeleteForm">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    @php
      $statusHasError = session('error') || $errors->any();
      $statusMessage = $statusHasError
        ? (session('error') ?? $errors->first())
        : session('status');
    @endphp
    {{-- Modal status (success/error) --}}
    @if($statusMessage)
      <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header {{ $statusHasError ? 'bg-danger' : 'bg-success' }} text-white">
              <h5 class="modal-title" id="statusModalLabel">
                {{ $statusHasError ? 'Gagal' : 'Berhasil' }}
              </h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              {{ $statusMessage }}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Vendor scripts --}}
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/misc.js') }}"></script>
    {{-- Auto-open status modal --}}
    <script>
      (function () {
        var modal = document.getElementById('statusModal');
        if (modal && window.$ && typeof window.$.fn.modal === 'function') {
          window.$(modal).modal('show');
        }
      })();
    </script>
    {{-- Theme toggle --}}
    <script>
      (function () {
        var toggle = document.getElementById('themeToggle');
        if (!toggle) return;
        function applyTheme(theme) {
          document.documentElement.classList.toggle('theme-light', theme === 'light');
          toggle.innerHTML = theme === 'light'
            ? '<i class="mdi mdi-weather-night"></i>'
            : '<i class="mdi mdi-white-balance-sunny"></i>';
        }
        var currentTheme = localStorage.getItem('admin-theme') || 'dark';
        applyTheme(currentTheme);
        toggle.addEventListener('click', function () {
          currentTheme = currentTheme === 'light' ? 'dark' : 'light';
          localStorage.setItem('admin-theme', currentTheme);
          applyTheme(currentTheme);
        });
      })();
    </script>
    {{-- Konfirmasi hapus --}}
    <script>
      (function () {
        var modal = document.getElementById('confirmDeleteModal');
        if (!modal) return;
        document.addEventListener('click', function (event) {
          var trigger = event.target.closest('[data-delete-action]');
          if (!trigger) return;
          event.preventDefault();
          var action = trigger.getAttribute('data-delete-action');
          var form = document.getElementById('confirmDeleteForm');
          if (form && action) form.setAttribute('action', action);
          if (window.$ && typeof window.$.fn.modal === 'function') {
            window.$(modal).modal('show');
          }
        });
      })();
    </script>
    @stack('scripts')
  </body>
</html>
