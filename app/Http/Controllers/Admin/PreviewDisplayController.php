<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreviewDisplayController extends Controller
{
    public function edit(): View
    {
        // Ambil kategori yang disimpan.
        $settings = Setting::query()
            ->whereIn('key', [
                'display_content_categories',
            ])
            ->pluck('value', 'key');

        $categories = $this->availableCategories();
        $selected = [];
        $categorySetting = $settings->get('display_content_categories');
        if (!empty($categorySetting)) {
            $decoded = json_decode($categorySetting, true);
            if (is_array($decoded)) {
                $selected = array_values(array_filter($decoded, fn ($value) => is_string($value) && $value !== ''));
            }
        }
        if (empty($selected)) {
            $selected = $categories;
        }

        return view('admin.preview-display', [
            'categories' => $categories,
            'selectedCategories' => $selected,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Simpan kategori yang dipilih untuk display.
        $data = $request->validate([
            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', 'in:' . implode(',', $this->availableCategories())],
        ]);

        $categories = array_values(array_unique($data['categories'] ?? []));

        Setting::query()->updateOrCreate(
            ['key' => 'display_content_categories'],
            ['value' => json_encode($categories)]
        );

        return redirect()->route('admin.preview.edit')
            ->with('status', 'Pengaturan preview display berhasil disimpan.');
    }

    private function availableCategories(): array
    {
        // Daftar kategori yang diizinkan.
        return ['kegiatan', 'spp', 'sop', 'video', 'lain-lain'];
    }
}
