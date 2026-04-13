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
          <small class="text-muted d-block mt-2">Preview ini mengikuti pengaturan kategori konten dan warna latar display setelah disimpan.</small>
        </div>
      </div>
    </div>
    {{-- Pengaturan kategori konten --}}
    <div class="col-12 col-xl-4 col-xxl-3 grid-margin stretch-card preview-display-side">
      <div class="card preview-display-side-card">
        <div class="card-body">
          <h4 class="card-title theme-light-title">Pengaturan Display</h4>
          <form method="POST" action="{{ route('admin.preview.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="display_background_color">Warna Latar Display</label>
              <div class="d-flex align-items-center preview-display-color-inputs">
                <input
                  type="color"
                  id="display_background_color_picker"
                  class="preview-display-color-picker"
                  value="{{ old('display_background_color', $displayBackgroundColor) }}"
                >
                <input
                  type="text"
                  id="display_background_color"
                  name="display_background_color"
                  class="form-control preview-display-color-code-input"
                  value="{{ old('display_background_color', $displayBackgroundColor) }}"
                  placeholder="#0B0D18"
                  inputmode="text"
                  autocomplete="off"
                  spellcheck="false"
                >
              </div>
              @error('display_background_color')
                <div class="text-danger small mt-2">{{ $message }}</div>
              @enderror
              <small class="text-muted d-block mt-2">Masukkan kode hex seperti <code>#0B0D18</code>. Warna teks display menyesuaikan otomatis agar tetap mudah dibaca.</small>
            </div>

            <div
              class="preview-theme-swatch mb-3"
              id="previewThemeSwatch"
              style="background: {{ old('display_background_color', $displayBackgroundColor) }}; color: {{ $displayTextColor }};"
            >
              <div class="preview-theme-swatch-title">Pratinjau Tema</div>
              <div class="preview-theme-swatch-subtitle" id="previewThemeTextLabel">Warna teks otomatis: {{ $displayTextColor }}</div>
            </div>

            <h5 class="theme-light-title mb-3">Kategori Konten Tampil</h5>
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
      background: {{ $displayBackgroundColor }};
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

    .preview-display-color-inputs {
      gap: 0.75rem;
    }

    .preview-display-color-picker {
      width: 56px;
      height: 40px;
      padding: 0;
      border: none;
      background: transparent;
      cursor: pointer;
    }

    .preview-display-color-code-input {
      min-width: 132px;
      max-width: 160px;
      font-family: Consolas, Monaco, monospace;
      font-size: 0.85rem;
      text-transform: uppercase;
    }

    .preview-theme-swatch {
      border-radius: 12px;
      padding: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.14);
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
    }

    .preview-theme-swatch-title {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
    }

    .preview-theme-swatch-subtitle {
      opacity: 0.8;
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
      const colorPicker = document.getElementById('display_background_color_picker');
      const colorInput = document.getElementById('display_background_color');
      const themeSwatch = document.getElementById('previewThemeSwatch');
      const themeTextLabel = document.getElementById('previewThemeTextLabel');
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

      function normalizeHexColor(color) {
        if (!color) return '#0B0D18';

        let value = String(color).trim().toUpperCase();
        if (!value.startsWith('#')) {
          value = '#' + value;
        }

        if (!/^#([0-9A-F]{3}|[0-9A-F]{6})$/.test(value)) {
          return '#0B0D18';
        }

        if (value.length === 4) {
          value = '#' + value.slice(1).split('').map((char) => char + char).join('');
        }

        return value;
      }

      function hexToRgb(color) {
        const value = normalizeHexColor(color).slice(1);

        return {
          r: parseInt(value.slice(0, 2), 16),
          g: parseInt(value.slice(2, 4), 16),
          b: parseInt(value.slice(4, 6), 16),
        };
      }

      function luminance(color) {
        const { r, g, b } = hexToRgb(color);
        const channels = [r, g, b].map((channel) => {
          const normalized = channel / 255;

          return normalized <= 0.03928
            ? normalized / 12.92
            : Math.pow((normalized + 0.055) / 1.055, 2.4);
        });

        return (channels[0] * 0.2126) + (channels[1] * 0.7152) + (channels[2] * 0.0722);
      }

      function contrastRatio(firstColor, secondColor) {
        const firstLuminance = luminance(firstColor);
        const secondLuminance = luminance(secondColor);
        const lighter = Math.max(firstLuminance, secondLuminance);
        const darker = Math.min(firstLuminance, secondLuminance);

        return (lighter + 0.05) / (darker + 0.05);
      }

      function getTextColor(color) {
        const background = normalizeHexColor(color);
        const lightText = '#F8FAFC';
        const darkText = '#111827';

        return contrastRatio(background, lightText) >= contrastRatio(background, darkText)
          ? lightText
          : darkText;
      }

      function updateThemePreview(color) {
        const normalizedColor = normalizeHexColor(color);
        const textColor = getTextColor(normalizedColor);

        if (colorInput) {
          colorInput.value = normalizedColor;
        }

        if (colorPicker) {
          colorPicker.value = normalizedColor;
        }

        if (themeSwatch) {
          themeSwatch.style.background = normalizedColor;
          themeSwatch.style.color = textColor;
        }

        if (themeTextLabel) {
          themeTextLabel.textContent = 'Warna teks otomatis: ' + textColor;
        }

        box.style.background = normalizedColor;
      }

      if (colorInput) {
        updateThemePreview(colorInput.value);
      }

      if (colorPicker) {
        colorPicker.addEventListener('input', function () {
          updateThemePreview(colorPicker.value);
        });
      }

      if (colorInput) {
        colorInput.addEventListener('input', function () {
          const value = String(colorInput.value || '').trim();
          if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(value)) {
            updateThemePreview(value);
          }
        });

        colorInput.addEventListener('blur', function () {
          updateThemePreview(colorInput.value);
        });
      }
    })();
  </script>
@endpush
