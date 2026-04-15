{{-- Form galeri (create/edit) --}}
@csrf
<div class="form-group">
  <label for="title">Judul <span class="text-danger">*</span></label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $gallery->title ?? '') }}" required>
</div>
<div class="form-group">
  <label for="image">Gambar <span class="text-danger">*</span></label>
  <input type="file" name="image" id="image" class="form-control"
    @if(empty($gallery?->image_path)) required @endif>
  @if(!empty($gallery?->image_path))
    <div class="mt-2">
      <img src="{{ asset('storage/' . $gallery->image_path) }}"
           alt="Gambar saat ini"
           class="admin-preview-image">
    </div>
  @endif
</div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="starts_at">Mulai</label>
    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
      value="{{ old('starts_at', optional($gallery->starts_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
  <div class="form-group col-md-6">
    <label for="ends_at">Selesai</label>
    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control"
      value="{{ old('ends_at', optional($gallery->ends_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
</div>

