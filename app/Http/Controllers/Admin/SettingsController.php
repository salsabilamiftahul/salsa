<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\UploadValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        // Ambil setting yang dibutuhkan di halaman admin.
        $settings = Setting::query()
            ->whereIn('key', [
                'institution_name',
                'logo_path',
                'service_hours_weekday_start',
                'service_hours_weekday_end',
                'service_hours_friday_start',
                'service_hours_friday_end',
                'service_hours_weekend_start',
                'service_hours_weekend_end',
                'service_hours_weekday',
                'service_hours_friday',
                'service_hours_weekend',
                'main_content_image_interval_seconds',
                'gallery_interval_seconds',
                'marquee_messages',
                'marquee_duration_seconds',
            ])
            ->pluck('value', 'key');

        // Support data lama yang disimpan sebagai "08:00 - 16:00".
        $parseLegacyTimeRange = function (?string $value): array {
            if (!$value) {
                return [null, null];
            }
            if (stripos($value, 'tutup') !== false) {
                return [null, null];
            }
            $parts = preg_split('/\s*-\s*/', $value);
            if (count($parts) >= 2) {
                return [trim($parts[0]), trim($parts[1])];
            }
            return [null, null];
        };

        [$legacyWeekdayStart, $legacyWeekdayEnd] = $parseLegacyTimeRange($settings->get('service_hours_weekday'));
        [$legacyFridayStart, $legacyFridayEnd] = $parseLegacyTimeRange($settings->get('service_hours_friday'));
        [$legacyWeekendStart, $legacyWeekendEnd] = $parseLegacyTimeRange($settings->get('service_hours_weekend'));

        return view('admin.settings', [
            'institutionName' => $settings->get('institution_name', 'Puskot Jogja'),
            'logoPath' => $settings->get('logo_path'),
            'serviceHoursWeekdayStart' => $settings->get('service_hours_weekday_start') ?? $legacyWeekdayStart ?? '08:00',
            'serviceHoursWeekdayEnd' => $settings->get('service_hours_weekday_end') ?? $legacyWeekdayEnd ?? '16:00',
            'serviceHoursFridayStart' => $settings->get('service_hours_friday_start') ?? $legacyFridayStart ?? '08:00',
            'serviceHoursFridayEnd' => $settings->get('service_hours_friday_end') ?? $legacyFridayEnd ?? '15:00',
            'serviceHoursWeekendStart' => $settings->get('service_hours_weekend_start') ?? $legacyWeekendStart,
            'serviceHoursWeekendEnd' => $settings->get('service_hours_weekend_end') ?? $legacyWeekendEnd,
            'mainContentImageIntervalSeconds' => (int) $settings->get('main_content_image_interval_seconds', 8),
            'galleryIntervalSeconds' => (int) $settings->get('gallery_interval_seconds', 6),
            'marqueeMessages' => $settings->get('marquee_messages', ''),
            'marqueeDurationSeconds' => (int) $settings->get('marquee_duration_seconds', 30),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Validasi input pengaturan.
        $data = $request->validate([
            'institution_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', ...UploadValidation::imageRules()],
            'service_hours_weekday_start' => ['required', 'date_format:H:i'],
            'service_hours_weekday_end' => ['required', 'date_format:H:i'],
            'service_hours_friday_start' => ['required', 'date_format:H:i'],
            'service_hours_friday_end' => ['required', 'date_format:H:i'],
            'service_hours_weekend_start' => ['nullable', 'date_format:H:i', 'required_with:service_hours_weekend_end'],
            'service_hours_weekend_end' => ['nullable', 'date_format:H:i', 'required_with:service_hours_weekend_start'],
            'main_content_image_interval_seconds' => ['required', 'integer', 'min:2', 'max:300'],
            'gallery_interval_seconds' => ['required', 'integer', 'min:2', 'max:120'],
            'marquee_messages' => ['nullable', 'string'],
            'marquee_duration_seconds' => ['required', 'integer', 'min:10', 'max:300'],
        ]);

        // Simpan nama instansi.
        Setting::query()->updateOrCreate(
            ['key' => 'institution_name'],
            ['value' => $data['institution_name']]
        );

        if ($request->hasFile('logo')) {
            $currentLogoPath = Setting::query()->where('key', 'logo_path')->value('value');
            $logoFile = $request->file('logo');
            $logoFileName = 'branding/' . UploadValidation::storedFileName($logoFile);

            File::ensureDirectoryExists(public_path('branding'));
            $logoFile->move(public_path('branding'), basename($logoFileName));

            if ($currentLogoPath && str_starts_with($currentLogoPath, 'branding/')) {
                File::delete(public_path($currentLogoPath));
            }

            $logoPath = $logoFileName;
            Setting::query()->updateOrCreate(['key' => 'logo_path'], ['value' => $logoPath]);
        }

        // Simpan jam layanan.
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_weekday_start'],
            ['value' => $data['service_hours_weekday_start']]
        );
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_weekday_end'],
            ['value' => $data['service_hours_weekday_end']]
        );
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_friday_start'],
            ['value' => $data['service_hours_friday_start']]
        );
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_friday_end'],
            ['value' => $data['service_hours_friday_end']]
        );
        $weekendStart = $data['service_hours_weekend_start'] ?? null;
        $weekendEnd = $data['service_hours_weekend_end'] ?? null;
        if ($weekendStart === '') {
            $weekendStart = null;
        }
        if ($weekendEnd === '') {
            $weekendEnd = null;
        }
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_weekend_start'],
            ['value' => $weekendStart]
        );
        Setting::query()->updateOrCreate(
            ['key' => 'service_hours_weekend_end'],
            ['value' => $weekendEnd]
        );
        // Simpan durasi konten.
        Setting::query()->updateOrCreate(
            ['key' => 'main_content_image_interval_seconds'],
            ['value' => $data['main_content_image_interval_seconds']]
        );
        Setting::query()->updateOrCreate(
            ['key' => 'gallery_interval_seconds'],
            ['value' => $data['gallery_interval_seconds']]
        );
        // Simpan teks berjalan.
        Setting::query()->updateOrCreate(
            ['key' => 'marquee_messages'],
            ['value' => $data['marquee_messages'] ?? '']
        );
        Setting::query()->updateOrCreate(
            ['key' => 'marquee_duration_seconds'],
            ['value' => $data['marquee_duration_seconds']]
        );

        return redirect()->route('admin.settings.edit')
            ->with('status', 'Pengaturan berhasil disimpan.');
    }
}
