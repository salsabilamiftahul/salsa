@extends('layouts.admin')

@section('title', 'Pengaturan Admin')

@section('content')
  {{-- Form pengaturan global display --}}
  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title theme-light-title">Pengaturan Tampilan</h4>
            {{-- Identitas instansi --}}
            <div class="form-group">
              <label for="institution_name">Nama Instansi <span class="text-danger">*</span></label>
              <input type="text" name="institution_name" id="institution_name" class="form-control" value="{{ old('institution_name', $institutionName) }}">
            </div>
            <div class="form-group">
              <label for="logo">Logo Instansi</label>
              <input type="file" name="logo" id="logo" class="form-control">
              @if($logoUrl)
                <small class="text-muted d-block mb-2">Logo saat ini:</small>
                <div class="d-flex align-items-center">
                  <img src="{{ $logoUrl }}" alt="Logo saat ini" style="height: 80px; width: auto; object-fit: contain;">
                </div>
              @endif
            </div>
            {{-- Jam layanan --}}
            <div class="form-group">
              <label>Jam Layanan Senin-Kamis <span class="text-danger">*</span></label>
              <div class="form-row">
                <div class="col">
                  <input type="time" name="service_hours_weekday_start" class="form-control"
                    value="{{ old('service_hours_weekday_start', $serviceHoursWeekdayStart) }}">
                </div>
                <div class="col">
                  <input type="time" name="service_hours_weekday_end" class="form-control"
                    value="{{ old('service_hours_weekday_end', $serviceHoursWeekdayEnd) }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Jam Layanan Jumat <span class="text-danger">*</span></label>
              <div class="form-row">
                <div class="col">
                  <input type="time" name="service_hours_friday_start" class="form-control"
                    value="{{ old('service_hours_friday_start', $serviceHoursFridayStart) }}">
                </div>
                <div class="col">
                  <input type="time" name="service_hours_friday_end" class="form-control"
                    value="{{ old('service_hours_friday_end', $serviceHoursFridayEnd) }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Jam Layanan Sabtu-Minggu</label>
              <div class="form-row">
                <div class="col">
                  <input type="time" name="service_hours_weekend_start" class="form-control"
                    value="{{ old('service_hours_weekend_start', $serviceHoursWeekendStart) }}">
                </div>
                <div class="col">
                  <input type="time" name="service_hours_weekend_end" class="form-control"
                    value="{{ old('service_hours_weekend_end', $serviceHoursWeekendEnd) }}">
                </div>
              </div>
              <small class="text-muted">Kosongkan jika layanan tutup.</small>
            </div>
            {{-- Durasi konten display --}}
            <div class="form-group">
              <label for="main_content_image_interval_seconds">Durasi Konten (detik) <span class="text-danger">*</span></label>
              <input type="number" name="main_content_image_interval_seconds" id="main_content_image_interval_seconds" class="form-control"
                     min="2" max="300" value="{{ old('main_content_image_interval_seconds', $mainContentImageIntervalSeconds) }}">
              <small class="text-muted">Minimal 2 detik.</small>
            </div>
            <div class="form-group">
              <label for="gallery_interval_seconds">Durasi Galeri Foto (detik) <span class="text-danger">*</span></label>
              <input type="number" name="gallery_interval_seconds" id="gallery_interval_seconds" class="form-control"
                     min="2" max="120" value="{{ old('gallery_interval_seconds', $galleryIntervalSeconds) }}">
              <small class="text-muted">Minimal 2 detik.</small>
            </div>
            {{-- Teks berjalan --}}
            <div class="form-group">
              <label for="marquee_messages">Teks Berjalan</label>
              <textarea name="marquee_messages" id="marquee_messages" class="form-control" rows="4"
                placeholder="Satu pesan per baris.">{{ old('marquee_messages', $marqueeMessages) }}</textarea>
              <small class="text-muted">Tulis lebih dari satu pesan, pisahkan dengan baris baru.</small>
            </div>
            <div class="form-group">
              <label for="marquee_duration_seconds">Durasi Teks Berjalan (detik) <span class="text-danger">*</span></label>
              <input type="number" name="marquee_duration_seconds" id="marquee_duration_seconds" class="form-control"
                     min="10" max="300" value="{{ old('marquee_duration_seconds', $marqueeDurationSeconds) }}">
              <small class="text-muted">Semakin besar nilainya, teks berjalan lebih pelan.</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
  </form>
@endsection
