<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Puskot')</title>
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/custom-overrides.css') }}">
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
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-6 mx-auto">
              <div class="card">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-outline-light btn-sm theme-toggle-btn" id="themeToggle" aria-label="Ubah tema">
                      <i class="mdi mdi-white-balance-sunny"></i>
                    </button>
                  </div>
                  @yield('content')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/js/misc.js') }}"></script>
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
    @stack('scripts')
  </body>
</html>
