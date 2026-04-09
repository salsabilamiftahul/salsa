{{-- Form agenda (create/edit) --}}
@csrf
<div class="form-group">
  <label for="title">Nama Agenda <span class="text-danger">*</span></label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $agenda->title ?? '') }}" required>
</div>
<div class="form-row">
  <div class="form-group col-md-4">
    <label for="agenda_date">Tanggal <span class="text-danger">*</span></label>
    <input type="date" name="agenda_date" id="agenda_date" class="form-control"
      value="{{ old('agenda_date', optional($agenda->starts_at ?? null)->format('Y-m-d')) }}" required>
  </div>
  <div class="form-group col-md-4">
    <label for="agenda_time">Jam Mulai <span class="text-danger">*</span></label>
    <input type="time" name="agenda_time" id="agenda_time" class="form-control"
      value="{{ old('agenda_time', optional($agenda->starts_at ?? null)->format('H:i')) }}" required>
  </div>
  <div class="form-group col-md-4">
    <label for="agenda_time_end">Jam Selesai</label>
    <input type="time" name="agenda_time_end" id="agenda_time_end" class="form-control"
      value="{{ old('agenda_time_end', optional($agenda->ends_at ?? null)->format('H:i')) }}">
  </div>
</div>
<div class="form-group form-check">
  <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
    {{ old('is_active', $agenda->is_active ?? true) ? 'checked' : '' }}>
  <label for="is_active" class="form-check-label">Aktif</label>
</div>
