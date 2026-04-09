@extends('layouts.admin')

@section('title', 'Preview Display')

@section('content')
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Preview Display</h3>
  </div>

  <div class="row preview-display-row">
    {{-- Preview iframe --}}
    <div class="col-12 col-xl-8 col-xxl-9 grid-margin stretch-card preview-display-main">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title theme-light-title">Preview Display</h4>
          <div class="preview-display-box">
            <div class="preview-display-stage" data-base-width="1920" data-base-height="1080">
              <iframe title="Preview Display" src="{{ route('display') }}" loading="lazy"></iframe>
            </div>
          </div>
          <small class="text-muted d-block mt-2">Preview ini mengikuti pengaturan kategori konten.</small>
        </div>
      </div>
    </div>
    {{-- Pengaturan kategori konten --}}
    <div class="col-12 col-xl-4 col-xxl-3 grid-margin stretch-card preview-display-side">
      <div class="card preview-display-side-card">
        <div class="card-body">
          <h4 class="card-title theme-light-title">Kategori Konten Tampil</h4>
          <form method="POST" action="{{ route('admin.preview.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group preview-display-category-list">
              @php
                $categoryLabels = [
                  'kegiatan' => 'Kegiatan',
                  'spp' => 'SPP',
                  'sop' => 'SOP',
                  'video' => 'Video',
                  'lain-lain' => 'Lain-lain',
                ];
              @endphp
              @foreach($categories as $category)
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="category_{{ $category }}" name="categories[]"
                    value="{{ $category }}" {{ in_array($category, $selectedCategories ?? []) ? 'checked' : '' }}>
                  <label class="form-check-label" for="category_{{ $category }}">
                    {{ $categoryLabels[$category] ?? ucfirst($category) }}
                  </label>
                </div>
              @endforeach
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Simpan Pengaturan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Kotak preview 16:9 */
    .preview-display-box {
      position: relative;
      width: 100%;
      aspect-ratio: 16 / 9;
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      overflow: hidden;
      background: #0b0d18;
    }

    .preview-display-stage {
      position: absolute;
      top: 0;
      left: 0;
      width: 1920px;
      height: 1080px;
      transform-origin: top left;
    }

    .preview-display-stage iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Panel pengaturan */
    .preview-display-side-card .card-body {
      padding: 1rem;
    }

    .preview-display-category-list .form-check {
      margin-bottom: 0.5rem !important;
    }

    .preview-display-category-list .form-check-label {
      font-size: 0.9rem;
    }
  </style>
@endpush

@push('scripts')
  {{-- Skala preview agar selalu muat di box --}}
  <script>
    (function () {
      const box = document.querySelector('.preview-display-box');
      const stage = document.querySelector('.preview-display-stage');
      if (!box || !stage) return;

      const baseWidth = Number(stage.dataset.baseWidth) || 1920;
      const baseHeight = Number(stage.dataset.baseHeight) || 1080;

      function rescalePreview() {
        const rect = box.getBoundingClientRect();
        const scale = Math.min(rect.width / baseWidth, rect.height / baseHeight);
        const offsetX = (rect.width - baseWidth * scale) / 2;
        const offsetY = (rect.height - baseHeight * scale) / 2;

        stage.style.transform = 'scale(' + scale + ')';
        stage.style.left = offsetX + 'px';
        stage.style.top = offsetY + 'px';
      }

      rescalePreview();
      window.addEventListener('resize', rescalePreview);
    })();
  </script>
@endpush
