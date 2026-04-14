@extends('layouts.admin')

@section('title', 'Preview Display')

@section('content')
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Preview Display</h3>
  </div>

  <form method="POST" action="{{ route('admin.preview.update') }}">
    @csrf
    @method('PUT')
    <div class="row preview-display-row">
      <div class="col-12 col-xl-8 col-xxl-9 preview-display-main-column">
        {{-- Preview iframe --}}
        <div class="grid-margin stretch-card preview-display-main">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title theme-light-title">Preview Display</h4>
              <div class="preview-display-box">
                <div class="preview-display-stage" data-base-width="1920" data-base-height="1080">
                  <iframe title="Preview Display" src="{{ route('display') }}" loading="lazy"></iframe>
                </div>
              </div>
              <small class="text-muted d-block mt-2">Preview ini mengikuti pengaturan kategori konten dan tema display setelah disimpan.</small>
            </div>
          </div>
        </div>

        {{-- Pengaturan tema display di bawah preview --}}
        <div class="grid-margin stretch-card preview-display-theme-card">
          <div class="card preview-display-side-card">
            <div class="card-body">
              <h4 class="card-title theme-light-title">Pengaturan Tema Display</h4>

              <div class="preview-theme-controls">
                <div class="form-group preview-theme-control">
                  <label for="display_background_color">Warna Latar</label>
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
                </div>

                <div class="form-group preview-theme-control">
                  <label for="display_text_color">Warna Teks</label>
                  <div class="d-flex align-items-center preview-display-color-inputs">
                    <input
                      type="color"
                      id="display_text_color_picker"
                      class="preview-display-color-picker"
                      value="{{ old('display_text_color', $displayTextColor) }}"
                    >
                    <input
                      type="text"
                      id="display_text_color"
                      name="display_text_color"
                      class="form-control preview-display-color-code-input"
                      value="{{ old('display_text_color', $displayTextColor) }}"
                      placeholder="#F8FAFC"
                      inputmode="text"
                      autocomplete="off"
                      spellcheck="false"
                    >
                  </div>
                  @error('display_text_color')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group preview-theme-control">
                  <label for="display_card_background_color">Warna Card</label>
                  <div class="d-flex align-items-center preview-display-color-inputs">
                    <input
                      type="color"
                      id="display_card_background_color_picker"
                      class="preview-display-color-picker"
                      value="{{ old('display_card_background_color', $displayCardBackgroundColor) }}"
                    >
                    <input
                      type="text"
                      id="display_card_background_color"
                      name="display_card_background_color"
                      class="form-control preview-display-color-code-input"
                      value="{{ old('display_card_background_color', $displayCardBackgroundColor) }}"
                      placeholder="#151B29"
                      inputmode="text"
                      autocomplete="off"
                      spellcheck="false"
                    >
                  </div>
                  @error('display_card_background_color')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div
                class="preview-theme-swatch mb-3"
                id="previewThemeSwatch"
                style="background: {{ old('display_background_color', $displayBackgroundColor) }}; color: {{ old('display_text_color', $displayTextColor) }};"
              >
                <div class="preview-theme-swatch-title">Pratinjau Tema</div>
                <div class="preview-theme-swatch-subtitle" id="previewThemeTextLabel">Warna teks: {{ old('display_text_color', $displayTextColor) }}</div>
                <div
                  class="preview-theme-swatch-card mt-3"
                  id="previewThemeCard"
                  style="background: {{ old('display_card_background_color', $displayCardBackgroundColor) }};"
                >
                  Contoh warna card display
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm">Simpan Pengaturan Tema</button>
            </div>
          </div>
        </div>
      </div>

      {{-- Kategori tampil di samping kanan preview --}}
      <div class="col-12 col-xl-4 col-xxl-3 grid-margin preview-display-side">
        <div class="card preview-display-side-card">
          <div class="card-body">
            <h4 class="theme-light-title mb-3">Kategori Konten Tampil</h4>
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
            <button type="submit" class="btn btn-primary btn-sm">Simpan Pengaturan Kategori</button>
          </div>
        </div>
      </div>
    </div>
  </form>
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

    .preview-theme-controls {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
    }

    .preview-theme-control {
      margin-bottom: 0;
    }

    .preview-theme-control label {
      font-size: 0.85rem;
      margin-bottom: 0.35rem;
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
      min-width: 0;
      max-width: 100%;
      font-family: Consolas, Monaco, monospace;
      font-size: 0.85rem;
      text-transform: uppercase;
    }

    @media (min-width: 768px) {
      .preview-theme-controls {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (min-width: 1200px) {
      .preview-theme-controls {
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }
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

    .preview-theme-swatch-card {
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.16);
      padding: 0.75rem;
      font-size: 0.88rem;
      font-weight: 600;
    }
  </style>
@endpush

@push('scripts')
  {{-- Skala preview agar selalu muat di box --}}
  <script>
    (function () {
      const box = document.querySelector('.preview-display-box');
      const stage = document.querySelector('.preview-display-stage');
      const backgroundPicker = document.getElementById('display_background_color_picker');
      const backgroundInput = document.getElementById('display_background_color');
      const textPicker = document.getElementById('display_text_color_picker');
      const textInput = document.getElementById('display_text_color');
      const cardPicker = document.getElementById('display_card_background_color_picker');
      const cardInput = document.getElementById('display_card_background_color');
      const themeSwatch = document.getElementById('previewThemeSwatch');
      const themeTextLabel = document.getElementById('previewThemeTextLabel');
      const themeCard = document.getElementById('previewThemeCard');
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

      function normalizeHexColor(color, fallback) {
        if (!color) return fallback;

        let value = String(color).trim().toUpperCase();
        if (!value.startsWith('#')) {
          value = '#' + value;
        }

        if (!/^#([0-9A-F]{3}|[0-9A-F]{6})$/.test(value)) {
          return fallback;
        }

        if (value.length === 4) {
          value = '#' + value.slice(1).split('').map((char) => char + char).join('');
        }

        return value;
      }

      function syncColorInputs(inputEl, pickerEl, fallbackColor) {
        if (!inputEl || !pickerEl) return fallbackColor;
        const normalized = normalizeHexColor(inputEl.value || pickerEl.value, fallbackColor);
        inputEl.value = normalized;
        pickerEl.value = normalized;
        return normalized;
      }

      function updateThemePreview() {
        const backgroundColor = syncColorInputs(backgroundInput, backgroundPicker, '#0B0D18');
        const textColor = syncColorInputs(textInput, textPicker, '#F8FAFC');
        const cardColor = syncColorInputs(cardInput, cardPicker, '#151B29');
        if (themeSwatch) {
          themeSwatch.style.background = backgroundColor;
          themeSwatch.style.color = textColor;
        }
        if (themeTextLabel) {
          themeTextLabel.textContent = 'Warna teks: ' + textColor;
        }
        if (themeCard) {
          themeCard.style.background = cardColor;
          themeCard.style.color = textColor;
        }
        box.style.background = backgroundColor;
      }

      updateThemePreview();

      [backgroundPicker, textPicker, cardPicker].forEach(function (picker) {
        if (!picker) return;
        picker.addEventListener('input', updateThemePreview);
      });

      [backgroundInput, textInput, cardInput].forEach(function (input) {
        if (!input) return;
        input.addEventListener('input', function () {
          const value = String(input.value || '').trim();
          if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(value)) {
            updateThemePreview();
          }
        });
        input.addEventListener('blur', updateThemePreview);
      });
    })();
  </script>
@endpush
