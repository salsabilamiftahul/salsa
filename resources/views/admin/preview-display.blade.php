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
              <small class="text-muted d-block mt-2">Preview warna ditampilkan langsung. Klik simpan untuk menerapkan permanen ke halaman display.</small>
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
                      value="{{ substr(\App\Support\DisplayTheme::normalizeHexColor(old('display_background_color', $displayBackgroundColor), '#0B0D18'), 0, 7) }}"
                    >
                    <input
                      type="text"
                      id="display_background_color"
                      name="display_background_color"
                      class="form-control preview-display-color-code-input"
                      value="{{ old('display_background_color', $displayBackgroundColor) }}"
                      placeholder="#0B0D18 atau #0B0D18CC"
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
                      value="{{ substr(\App\Support\DisplayTheme::normalizeHexColor(old('display_text_color', $displayTextColor), '#F8FAFC'), 0, 7) }}"
                    >
                    <input
                      type="text"
                      id="display_text_color"
                      name="display_text_color"
                      class="form-control preview-display-color-code-input"
                      value="{{ old('display_text_color', $displayTextColor) }}"
                      placeholder="#F8FAFC atau #F8FAFCCC"
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
                      value="{{ substr(\App\Support\DisplayTheme::normalizeHexColor(old('display_card_background_color', $displayCardBackgroundColor), '#151B29'), 0, 7) }}"
                    >
                    <input
                      type="text"
                      id="display_card_background_color"
                      name="display_card_background_color"
                      class="form-control preview-display-color-code-input"
                      value="{{ old('display_card_background_color', $displayCardBackgroundColor) }}"
                      placeholder="#151B29 atau #151B29CC"
                      inputmode="text"
                      autocomplete="off"
                      spellcheck="false"
                    >
                  </div>
                  <small class="text-muted d-block mt-1">Format transparan: <code>#RRGGBBAA</code> (AA di belakang).</small>
                  @error('display_card_background_color')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
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
      if (!box || !stage) return;
      const previewIframe = stage.querySelector('iframe');

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

        if (!/^#([0-9A-F]{3}|[0-9A-F]{4}|[0-9A-F]{6}|[0-9A-F]{8})$/.test(value)) {
          return fallback;
        }

        if (value.length === 4) {
          value = '#' + value.slice(1).split('').map((char) => char + char).join('');
        } else if (value.length === 5) {
          value = '#' + value.slice(1).split('').map((char) => char + char).join('');
        }

        return value;
      }

      function toOpaqueHex(hexColor) {
        const normalized = normalizeHexColor(hexColor, '#000000');
        return normalized.length === 9 ? normalized.slice(0, 7) : normalized;
      }

      function syncColorInputs(inputEl, pickerEl, fallbackColor, source) {
        if (!inputEl || !pickerEl) return fallbackColor;
        let normalized = normalizeHexColor(inputEl.value || fallbackColor, fallbackColor);

        if (source === 'picker') {
          const pickedColor = normalizeHexColor(pickerEl.value || fallbackColor, fallbackColor);
          // Picker bawaan browser tidak punya alpha, jadi gunakan warna solid (FF).
          normalized = toOpaqueHex(pickedColor);
        }

        inputEl.value = normalized;
        pickerEl.value = toOpaqueHex(normalized);
        return normalized;
      }

      function hexToRgb(hexColor) {
        const normalized = toOpaqueHex(hexColor).slice(1);
        return [
          parseInt(normalized.slice(0, 2), 16),
          parseInt(normalized.slice(2, 4), 16),
          parseInt(normalized.slice(4, 6), 16),
        ];
      }

      function rgba(hexColor, alpha) {
        const rgb = hexToRgb(hexColor);
        const clampedAlpha = Math.max(0, Math.min(1, Number(alpha) || 0));
        return 'rgba(' + rgb[0] + ', ' + rgb[1] + ', ' + rgb[2] + ', ' + clampedAlpha.toFixed(3) + ')';
      }

      function mix(baseColor, targetColor, ratio) {
        const base = hexToRgb(baseColor);
        const target = hexToRgb(targetColor);
        const clampedRatio = Math.max(0, Math.min(1, Number(ratio) || 0));
        const red = Math.round(base[0] + ((target[0] - base[0]) * clampedRatio));
        const green = Math.round(base[1] + ((target[1] - base[1]) * clampedRatio));
        const blue = Math.round(base[2] + ((target[2] - base[2]) * clampedRatio));

        return '#' + [red, green, blue]
          .map((value) => value.toString(16).padStart(2, '0'))
          .join('')
          .toUpperCase();
      }

      function relativeLuminance(hexColor) {
        const rgb = hexToRgb(hexColor).map(function (channel) {
          const value = channel / 255;
          return value <= 0.03928
            ? value / 12.92
            : Math.pow((value + 0.055) / 1.055, 2.4);
        });
        return (rgb[0] * 0.2126) + (rgb[1] * 0.7152) + (rgb[2] * 0.0722);
      }

      function contrastRatio(firstColor, secondColor) {
        const first = relativeLuminance(firstColor);
        const second = relativeLuminance(secondColor);
        const lighter = Math.max(first, second);
        const darker = Math.min(first, second);

        return (lighter + 0.05) / (darker + 0.05);
      }

      function pickTextColor(backgroundColor, textColor) {
        const normalizedText = normalizeHexColor(textColor, '');
        if (normalizedText) {
          return normalizedText;
        }

        const lightText = '#F8FAFC';
        const darkText = '#111827';
        return contrastRatio(backgroundColor, lightText) >= contrastRatio(backgroundColor, darkText)
          ? lightText
          : darkText;
      }

      function buildThemeVariables(backgroundColor, textColor, cardColor) {
        const resolvedBackground = normalizeHexColor(backgroundColor, '#0B0D18');
        const resolvedText = pickTextColor(resolvedBackground, textColor);
        const usesDarkText = toOpaqueHex(resolvedText) === '#111827';
        const fallbackCard = mix(resolvedBackground, resolvedText, usesDarkText ? 0.1 : 0.14);
        const resolvedCard = normalizeHexColor(cardColor, fallbackCard);
        const hasCardAlpha = resolvedCard.length === 9;
        const cardBackgroundValue = hasCardAlpha ? resolvedCard : rgba(resolvedCard, usesDarkText ? 0.86 : 0.92);
        const surfaceColor = mix(resolvedBackground, resolvedText, usesDarkText ? 0.14 : 0.06);
        const galleryOverlay = mix(resolvedBackground, resolvedText, usesDarkText ? 0.18 : 0.28);

        return {
          '--display-bg': resolvedBackground,
          '--display-text': resolvedText,
          '--display-muted': rgba(resolvedText, usesDarkText ? 0.72 : 0.78),
          '--display-border': rgba(resolvedText, usesDarkText ? 0.12 : 0.08),
          '--display-border-strong': rgba(resolvedText, usesDarkText ? 0.2 : 0.16),
          '--display-panel-bg': cardBackgroundValue,
          '--display-card-bg': cardBackgroundValue,
          '--display-surface-bg': rgba(surfaceColor, usesDarkText ? 0.88 : 0.9),
          '--display-marquee-bg': cardBackgroundValue,
          '--display-gallery-overlay': rgba(galleryOverlay, usesDarkText ? 0.88 : 0.92),
          '--display-shadow': usesDarkText ? 'rgba(15, 23, 42, 0.16)' : 'rgba(0, 0, 0, 0.3)',
        };
      }

      function applyThemeToIframe(themeVariables) {
        if (!previewIframe) return;

        let previewDocument = null;
        try {
          previewDocument = previewIframe.contentDocument;
        } catch (error) {
          return;
        }

        if (!previewDocument || !previewDocument.documentElement) return;

        const rootStyle = previewDocument.documentElement.style;
        Object.keys(themeVariables).forEach(function (cssVar) {
          rootStyle.setProperty(cssVar, themeVariables[cssVar]);
        });

        if (previewDocument.body) {
          previewDocument.body.style.background = 'var(--display-bg)';
          previewDocument.body.style.color = 'var(--display-text)';
        }
      }

      function updateThemePreview(preferredSource) {
        const source = preferredSource || {};
        const backgroundColor = syncColorInputs(backgroundInput, backgroundPicker, '#0B0D18', source.background || 'input');
        const textColor = syncColorInputs(textInput, textPicker, '#F8FAFC', source.text || 'input');
        const cardColor = syncColorInputs(cardInput, cardPicker, '#151B29', source.card || 'input');
        box.style.background = backgroundColor;
        applyThemeToIframe(buildThemeVariables(backgroundColor, textColor, cardColor));
      }

      updateThemePreview();

      if (previewIframe) {
        previewIframe.addEventListener('load', function () {
          updateThemePreview();
        });
      }

      if (backgroundPicker) {
        backgroundPicker.addEventListener('input', function () {
          updateThemePreview({ background: 'picker' });
        });
      }
      if (textPicker) {
        textPicker.addEventListener('input', function () {
          updateThemePreview({ text: 'picker' });
        });
      }
      if (cardPicker) {
        cardPicker.addEventListener('input', function () {
          updateThemePreview({ card: 'picker' });
        });
      }

      if (backgroundInput) {
        backgroundInput.addEventListener('input', function () {
          if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/.test(String(backgroundInput.value || '').trim())) {
            updateThemePreview({ background: 'input' });
          }
        });
        backgroundInput.addEventListener('blur', function () {
          updateThemePreview({ background: 'input' });
        });
      }

      if (textInput) {
        textInput.addEventListener('input', function () {
          if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/.test(String(textInput.value || '').trim())) {
            updateThemePreview({ text: 'input' });
          }
        });
        textInput.addEventListener('blur', function () {
          updateThemePreview({ text: 'input' });
        });
      }

      if (cardInput) {
        cardInput.addEventListener('input', function () {
          if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/.test(String(cardInput.value || '').trim())) {
            updateThemePreview({ card: 'input' });
          }
        });
        cardInput.addEventListener('blur', function () {
          updateThemePreview({ card: 'input' });
        });
      }
    })();
  </script>
@endpush
