{{-- Form konten utama (create/edit) --}}
@csrf
<div class="form-group">
  <label for="title">Judul <span class="text-danger">*</span></label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $mainContent->title ?? '') }}" required>
</div>
<div class="form-group">
  <label for="category">Kategori <span class="text-danger">*</span></label>
  <select name="category" id="category" class="form-control" required>
    @php
      $selectedCategory = old('category', $mainContent->category ?? 'lain-lain');
      $categories = [
        'kegiatan' => 'Kegiatan',
        'spp' => 'SPP',
        'sop' => 'SOP',
        'video' => 'Video',
        'lain-lain' => 'Lain-lain',
      ];
    @endphp
    @foreach($categories as $categoryValue => $categoryLabel)
      <option value="{{ $categoryValue }}" {{ $selectedCategory === $categoryValue ? 'selected' : '' }}>
        {{ $categoryLabel }}
      </option>
    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="media">Media (Gambar / Video) <span class="text-danger">*</span></label>
  <input type="file" name="media" id="media" class="form-control" accept="image/*,video/*">
  @if(!empty($mainContent?->image_path))
    <div class="mt-2">
      @if(($mainContent->media_type ?? 'image') === 'video')
        <video controls class="admin-preview-video">
          <source src="{{ asset('storage/' . $mainContent->image_path) }}">
        </video>
      @else
        <img src="{{ asset('storage/' . $mainContent->image_path) }}"
             alt="Media saat ini"
             class="admin-preview-image">
      @endif
    </div>
  @endif
  <small class="text-muted d-block">Dukungan file gambar atau video.</small>
</div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="starts_at">Mulai</label>
    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
      value="{{ old('starts_at', optional($mainContent->starts_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
  <div class="form-group col-md-6">
    <label for="ends_at">Selesai</label>
    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control"
      value="{{ old('ends_at', optional($mainContent->ends_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
</div>
<div class="form-group form-check">
  <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
    {{ old('is_active', $mainContent->is_active ?? true) ? 'checked' : '' }}>
  <label for="is_active" class="form-check-label">Aktif</label>
</div>
