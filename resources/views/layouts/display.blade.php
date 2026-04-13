<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Display TV')</title>
    <meta name="theme-color" content="{{ $displayTheme['backgroundColor'] ?? '#0B0D18' }}">
    {{-- Vendor styles --}}
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('corona-free-dark-bootstrap/template/assets/css/custom-overrides.css') }}">
    <style>
      :root {
        --display-bg: {{ $displayTheme['backgroundColor'] ?? '#0B0D18' }};
        --display-text: {{ $displayTheme['textColor'] ?? '#F8FAFC' }};
        --display-muted: {{ $displayTheme['mutedTextColor'] ?? 'rgba(248, 250, 252, 0.780)' }};
        --display-border: {{ $displayTheme['borderColor'] ?? 'rgba(248, 250, 252, 0.080)' }};
        --display-border-strong: {{ $displayTheme['borderStrongColor'] ?? 'rgba(248, 250, 252, 0.160)' }};
        --display-panel-bg: {{ $displayTheme['panelBackgroundColor'] ?? 'rgba(18, 23, 36, 0.600)' }};
        --display-card-bg: {{ $displayTheme['cardBackgroundColor'] ?? 'rgba(21, 27, 41, 0.920)' }};
        --display-surface-bg: {{ $displayTheme['surfaceBackgroundColor'] ?? 'rgba(28, 34, 49, 0.900)' }};
        --display-marquee-bg: {{ $displayTheme['marqueeBackgroundColor'] ?? 'rgba(22, 27, 40, 0.950)' }};
        --display-gallery-overlay: {{ $displayTheme['galleryOverlayColor'] ?? 'rgba(6, 8, 16, 0.920)' }};
        --display-shadow: {{ $displayTheme['shadowColor'] ?? 'rgba(0, 0, 0, 0.300)' }};
      }
    </style>
    {{-- Styles khusus display --}}
    @if($logoUrl)
      <link rel="shortcut icon" href="{{ $logoUrl }}">
    @else
      <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64'%3E%3Crect width='100%25' height='100%25' fill='%231b1e2b'/%3E%3Ctext x='50%25' y='50%25' fill='%23ffffff' font-family='Arial' font-size='36' font-weight='700' text-anchor='middle' dominant-baseline='central'%3EP%3C/text%3E%3C/svg%3E">
    @endif
    @stack('styles')
  </head>
  <body class="display-tv">
    @yield('content')
    {{-- Vendor scripts --}}
    <script src="{{ asset('corona-free-dark-bootstrap/template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    @stack('scripts')
  </body>
</html>
