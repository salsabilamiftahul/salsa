@csrf
<div class="form-group">
  <label for="name">Nama Admin <span class="text-danger">*</span></label>
  <input
    type="text"
    name="name"
    id="name"
    class="form-control @error('name') is-invalid @enderror"
    value="{{ old('name', $admin->name ?? '') }}"
    placeholder="Contoh: Admin Utama"
    required
  >
  @error('name')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>

<div class="form-group">
  <label for="username">Username Admin <span class="text-danger">*</span></label>
  <input
    type="text"
    name="username"
    id="username"
    class="form-control @error('username') is-invalid @enderror"
    value="{{ old('username', $admin->username ?? '') }}"
    placeholder="Contoh: admin_perpus"
    required
  >
  <small class="form-text text-muted">Username ini akan dipakai untuk login ke panel admin.</small>
  @error('username')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>

<div class="form-group">
  <label for="password">
    {{ isset($admin) && $admin->exists ? 'Password Baru' : 'Password' }}
    @unless(isset($admin) && $admin->exists)
      <span class="text-danger">*</span>
    @endunless
  </label>
  <input
    type="password"
    name="password"
    id="password"
    class="form-control @error('password') is-invalid @enderror"
    placeholder="{{ isset($admin) && $admin->exists ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
    @unless(isset($admin) && $admin->exists) required @endunless
  >
  @error('password')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>

<div class="form-group">
  <label for="password_confirmation">
    {{ isset($admin) && $admin->exists ? 'Konfirmasi Password Baru' : 'Konfirmasi Password' }}
    @unless(isset($admin) && $admin->exists)
      <span class="text-danger">*</span>
    @endunless
  </label>
  <input
    type="password"
    name="password_confirmation"
    id="password_confirmation"
    class="form-control"
    placeholder="Ulangi password"
    @unless(isset($admin) && $admin->exists) required @endunless
  >
</div>

