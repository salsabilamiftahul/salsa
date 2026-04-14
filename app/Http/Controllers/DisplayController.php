<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\GalleryItem;
use App\Models\MainContent;
use App\Models\Setting;
use App\Support\DisplayTheme;
use Illuminate\View\View;

class DisplayController extends Controller
{
    public function index(): View
    {
        $now = now();
        $today = $now->copy()->startOfDay();

        // Ambil semua konfigurasi display TV.
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
                'display_content_categories',
                'display_background_color',
                'display_text_color',
                'display_card_background_color',
            ])
            ->pluck('value', 'key');

        // Pecah teks berjalan (1 pesan per baris).
        $marqueeMessages = preg_split('/\r\n|\r|\n/', (string) $settings->get('marquee_messages', ''));
        $marqueeMessages = array_values(array_filter(array_map('trim', $marqueeMessages)));

        // Kategori konten yang ditampilkan di layar utama.
        $defaultCategories = ['kegiatan', 'spp', 'sop', 'video', 'lain-lain'];
        $categorySetting = $settings->get('display_content_categories');
        $selectedCategories = [];
        if (!empty($categorySetting)) {
            $decoded = json_decode($categorySetting, true);
            if (is_array($decoded)) {
                $selectedCategories = array_values(array_filter($decoded, fn ($value) => is_string($value) && $value !== ''));
            }
        }
        $displayCategories = $selectedCategories ?: $defaultCategories;

        // Dukungan format lama jam layanan (jika masih tersimpan sebagai "08:00 - 16:00").
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

        $legacyWeekday = $settings->get('service_hours_weekday');
        $legacyFriday = $settings->get('service_hours_friday');
        $legacyWeekend = $settings->get('service_hours_weekend');

        [$legacyWeekdayStart, $legacyWeekdayEnd] = $parseLegacyTimeRange($legacyWeekday);
        [$legacyFridayStart, $legacyFridayEnd] = $parseLegacyTimeRange($legacyFriday);
        [$legacyWeekendStart, $legacyWeekendEnd] = $parseLegacyTimeRange($legacyWeekend);
        $displayTheme = DisplayTheme::themeVariables(
            $settings->get('display_background_color'),
            $settings->get('display_text_color'),
            $settings->get('display_card_background_color')
        );

        return view('display.tv', [
            // Header display
            'institutionName' => $settings->get('institution_name', 'Puskot Jogja'),
            'logoPath' => $settings->get('logo_path'),
            // Jam layanan
            'serviceHoursWeekdayStart' => $settings->get('service_hours_weekday_start') ?? $legacyWeekdayStart ?? '08:00',
            'serviceHoursWeekdayEnd' => $settings->get('service_hours_weekday_end') ?? $legacyWeekdayEnd ?? '16:00',
            'serviceHoursFridayStart' => $settings->get('service_hours_friday_start') ?? $legacyFridayStart ?? '08:00',
            'serviceHoursFridayEnd' => $settings->get('service_hours_friday_end') ?? $legacyFridayEnd ?? '15:00',
            'serviceHoursWeekendStart' => $settings->get('service_hours_weekend_start') ?? $legacyWeekendStart,
            'serviceHoursWeekendEnd' => $settings->get('service_hours_weekend_end') ?? $legacyWeekendEnd,
            // Interval rotasi konten
            'mainContentImageIntervalSeconds' => (int) $settings->get('main_content_image_interval_seconds', 8),
            'galleryIntervalSeconds' => (int) $settings->get('gallery_interval_seconds', 6),
            // Teks berjalan
            'marqueeMessages' => $marqueeMessages,
            'marqueeDurationSeconds' => (int) $settings->get('marquee_duration_seconds', 30),
            'displayTheme' => $displayTheme,
            // Agenda hari ini dan agenda mendatang 
            'agendas' => Agenda::query()
                ->where(function ($query) use ($now, $today) {
                    $query->where('starts_at', '>=', $today)
                        ->orWhere(function ($subQuery) use ($now) {
                            $subQuery->where('starts_at', '<=', $now)
                                ->where('ends_at', '>=', $now);
                        });
                })
                ->orderBy('starts_at')
                ->limit(3)
                ->get(),
            // Konten utama dan rotator kanan
            'mainContents' => MainContent::query()
                ->active()
                ->whereIn('category', $displayCategories)
                ->orderByDesc('starts_at')
                ->get(),
            'galleries' => GalleryItem::query()->active()->orderBy('starts_at')->limit(3)->get(),
        ]);
    }
}
