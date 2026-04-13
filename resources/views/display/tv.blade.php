@extends('layouts.display')

@section('title', 'Display TV')

@section('content')
  <div class="tv-shell">
    {{-- Header: logo, nama instansi, dan waktu --}}
    <div class="container-fluid py-4 tv-screen">
      <div class="d-flex align-items-center justify-content-between tv-header pb-3 mb-4">
        <div class="d-flex align-items-center tv-brand">
          @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo" style="height: 96px;">
          @endif
          <div class="ml-3">
            <h3 class="mb-0 tv-title">{{ $institutionName }}</h3>
<small class="text-muted tv-subtitle fst-italic" style="font-style: italic;">System for Agenda and Library Services Access</small>
          </div>
        </div>
        <div class="text-right tv-time">
          <div class="tv-clock" id="tvClock">00:00:00</div>
          <div class="tv-date" id="tvDate">-</div>
        </div>
      </div>

      <div class="row">
        {{-- Konten utama --}}
        <div class="col-12 col-xl-9 grid-margin stretch-card tv-panel tv-panel-main">
          <div class="card tv-card tv-card-main">
            <div class="card-body">
              @if($mainContents->count())
                <div class="tv-main-rotator" data-interval="{{ $mainContentImageIntervalSeconds }}">
                  @foreach($mainContents as $mainContent)
                    @php
                      $isVideo = ($mainContent->media_type ?? 'image') === 'video';
                    @endphp
                    <div class="tv-main-rotator-item">
                      <div class="tv-main-frame">
                        @if($isVideo && $mainContent->image_path)
                          <video class="tv-main-media" muted playsinline>
                            <source src="{{ asset('storage/' . $mainContent->image_path) }}">
                          </video>
                        @else
                          <img class="tv-main-media" src="{{ $mainContent->image_path ? asset('storage/' . $mainContent->image_path) : asset('corona-free-dark-bootstrap/template/assets/images/dashboard/Rectangle.jpg') }}" alt="Konten Utama">
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-muted">Belum ada konten utama aktif.</div>
              @endif
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-3 tv-right-stack">
        <div class="row tv-right-row">
          {{-- Jam layanan --}}
          <div class="col-12 grid-margin">
            <div class="card tv-card tv-card-tight tv-card-right tv-service-hours-card">
              <div class="card-body">
                @php
                  $today = \Carbon\Carbon::now()->locale('id');
                  $todayKey = $today->isoFormat('dddd');
                  $weekdayLabel = $serviceHoursWeekdayStart && $serviceHoursWeekdayEnd
                    ? $serviceHoursWeekdayStart . ' - ' . $serviceHoursWeekdayEnd
                    : 'Tutup';
                  $fridayLabel = $serviceHoursFridayStart && $serviceHoursFridayEnd
                    ? $serviceHoursFridayStart . ' - ' . $serviceHoursFridayEnd
                    : 'Tutup';
                  $weekendLabel = $serviceHoursWeekendStart && $serviceHoursWeekendEnd
                    ? $serviceHoursWeekendStart . ' - ' . $serviceHoursWeekendEnd
                    : 'Tutup';
                  $todayHours = $weekendLabel;
                  if (in_array($todayKey, ['Senin','Selasa','Rabu','Kamis'])) {
                    $todayHours = $weekdayLabel;
                  } elseif ($todayKey === 'Jumat') {
                    $todayHours = $fridayLabel;
                  }
                  $isClosedToday = stripos($todayHours, 'tutup') !== false;
                @endphp
                <div class="d-flex align-items-center justify-content-between tv-section-header">
                  <h4 class="card-title tv-section-title tv-section-title--hours mb-0">
                    <span class="tv-section-text">Jam Layanan</span>
                  </h4>
                  <span class="tv-status-badge {{ $isClosedToday ? 'is-closed' : 'is-open' }}">
                    {{ $isClosedToday ? 'TUTUP' : 'BUKA' }}
                  </span>
                </div>
                <div class="tv-hours-list">
                  <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Senin-Kamis</span>
                    <span class="text-muted">{{ $weekdayLabel }}</span>
                  </div>
                  <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Jumat</span>
                    <span class="text-muted">{{ $fridayLabel }}</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span>Sabtu-Minggu</span>
                    <span class="text-muted">{{ $weekendLabel }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Agenda terdekat --}}
          <div class="col-12 grid-margin">
            <div class="card tv-card tv-card-tight tv-card-right tv-agenda-mini-card">
              <div class="card-body">
                <h4 class="card-title tv-section-title tv-section-title--agenda">
                  <span class="tv-section-text">Agenda Terdekat</span>
                </h4>
                @if($agendas->count())
                  <div class="tv-mini-table">
                    @foreach($agendas as $agenda)
                      @php
                        $startAt = $agenda->starts_at ? \Carbon\Carbon::parse($agenda->starts_at) : null;
                        $endAt = $agenda->ends_at ? \Carbon\Carbon::parse($agenda->ends_at) : null;
                        $dateLabel = $startAt
                          ? $startAt->locale('id')->isoFormat('dddd, D MMMM YYYY')
                          : '-';
                        $timeLabel = $startAt ? $startAt->format('H:i') : '-';
                        if ($startAt && $endAt) {
                          $timeLabel = $startAt->format('H:i') . ' - ' . $endAt->format('H:i');
                        }
                      @endphp
                      <div class="tv-mini-row">
                        <span class="tv-mini-value tv-mini-title">{{ $agenda->title }}</span>
                        <span class="tv-mini-value tv-mini-time">
                          <span class="tv-mini-date">{{ $dateLabel }}</span>
                          <span class="tv-mini-clock">{{ $timeLabel }}</span>
                        </span>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="text-muted">Belum ada agenda terdekat.</div>
                @endif
              </div>
            </div>
          </div>

          {{-- Galeri foto --}}
          <div class="col-12 grid-margin stretch-card">
            <div class="card tv-card tv-card-right">
              <div class="card-body">
                <h4 class="card-title tv-section-title tv-section-title--gallery">
                  <span class="tv-section-text">Galeri Foto</span>
                </h4>
                <div class="row tv-rotator" data-interval="{{ $galleryIntervalSeconds }}">
                  @forelse($galleries as $gallery)
                    <div class="col-12 mb-3 tv-rotator-item tv-rotator-block">
                      <div class="tv-gallery-card" style="background-image: url('{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : asset('corona-free-dark-bootstrap/template/assets/images/dashboard/img_6.jpg') }}');">
                        <div class="tv-gallery-title">{{ $gallery->title }}</div>
                      </div>
                    </div>
                  @empty
                    <div class="text-muted">Belum ada galeri aktif.</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- Teks berjalan --}}
    <div class="tv-marquee" style="--marquee-duration: {{ $marqueeDurationSeconds }}s;">
      <div class="marquee-track">
        @forelse($marqueeMessages as $message)
          <span class="marquee-item">{{ $message }}</span>
        @empty
          <span class="marquee-item">Informasi layanan akan diperbarui secara berkala.</span>
        @endforelse
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/display-tv.css') }}?v={{ filemtime(public_path('css/display-tv.css')) }}">
@endpush

@push('scripts')
  {{-- Jam realtime --}}
  <script>
    (function () {
      const clockEl = document.getElementById('tvClock');
      const dateEl = document.getElementById('tvDate');

      function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour12: false });
        const date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        clockEl.textContent = time;
        dateEl.textContent = date;
      }

      updateClock();
      setInterval(updateClock, 1000);
    })();
  </script>
  {{-- Rotasi galeri kanan --}}
  <script>
    (function () {
      const rotators = Array.from(document.querySelectorAll('.tv-rotator'));

      rotators.forEach((rotator) => {
        const items = Array.from(rotator.querySelectorAll('.tv-rotator-item'));
        if (items.length <= 1) {
          if (items[0]) items[0].classList.add('is-active');
          return;
        }

        let index = 0;
        const intervalSeconds = Number(rotator.dataset.interval || 8);
        const intervalMs = Math.max(intervalSeconds, 2) * 1000;
        const transitionMs = 650;

        items.forEach((item, i) => item.classList.toggle('is-active', i === index));

        setInterval(() => {
          const prevItem = items[index];
          prevItem.classList.remove('is-active');
          prevItem.classList.add('is-exiting');
          index = (index + 1) % items.length;
          items[index].classList.add('is-active');
          setTimeout(() => {
            prevItem.classList.remove('is-exiting');
          }, transitionMs);
        }, intervalMs);
      });
    })();
  </script>
  {{-- Rotasi konten utama (gambar/video) --}}
  <script>
    (function () {
      const rotator = document.querySelector('.tv-main-rotator');
      if (!rotator) return;

      const items = Array.from(rotator.querySelectorAll('.tv-main-rotator-item'));
      if (items.length === 0) return;

      const isSingleItem = items.length === 1;
      let index = 0;
      let previousIndex = null;
      const intervalSeconds = Number(rotator.dataset.interval || 8);
      const intervalMs = Math.max(intervalSeconds, 2) * 1000;
      const transitionMs = 800;
      let timerId = null;
      let currentVideo = null;

      function clearTimer() {
        if (timerId) {
          clearTimeout(timerId);
          timerId = null;
        }
      }

      function detachVideoListener() {
        if (currentVideo) {
          currentVideo.removeEventListener('ended', handleVideoEnded);
          currentVideo = null;
        }
      }

      function handleVideoEnded() {
        goNext();
      }

      function setActive(nextIndex) {
        clearTimer();
        detachVideoListener();

        items.forEach((item, i) => {
          const isActive = i === nextIndex;
          if (isActive) {
            item.classList.add('is-active');
          } else {
            item.classList.remove('is-active');
          }

          const video = item.querySelector('video');
          if (video) {
            video.loop = isSingleItem;
            if (isActive) {
              video.currentTime = 0;
              video.play().catch(() => {});
              currentVideo = video;
              if (!isSingleItem) {
                currentVideo.addEventListener('ended', handleVideoEnded, { once: true });
              }
            } else {
              video.pause();
            }
          }
        });

        if (previousIndex !== null && previousIndex !== nextIndex) {
          const prevItem = items[previousIndex];
          if (prevItem) {
            prevItem.classList.add('is-exiting');
            setTimeout(() => {
              prevItem.classList.remove('is-exiting');
            }, transitionMs);
          }
        }

        previousIndex = nextIndex;

        const activeItem = items[nextIndex];
        if (activeItem && !activeItem.querySelector('video')) {
          timerId = setTimeout(() => {
            goNext();
          }, intervalMs);
        }
      }

      function goNext() {
        index = (index + 1) % items.length;
        setActive(index);
      }

      setActive(index);

      if (items.length > 1) {
        // Rotasi dikendalikan oleh timer (gambar) atau event video ended.
      }
    })();
  </script>
  {{-- Duplikasi teks untuk marquee infinite --}}
  <script>
    (function () {
      const track = document.querySelector('.marquee-track');
      if (!track) return;
      const items = Array.from(track.children);
      if (items.length === 0) return;

      items.forEach((item) => {
        const clone = item.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);
      });
    })();
  </script>
@endpush
