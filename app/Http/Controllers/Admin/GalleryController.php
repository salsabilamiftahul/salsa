<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Support\UploadValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        // Pencarian sederhana berdasarkan judul.
        $search = trim((string) $request->query('q', ''));

        $query = GalleryItem::query()->latest();
        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return view('admin.galleries.index', [
            'galleries' => $query->paginate(10)->appends(['q' => $search]),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi input galeri.
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', ...UploadValidation::imageRules()],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = UploadValidation::storeUploadedFile($request->file('image'), 'galleries', 'public');
        }

        GalleryItem::query()->create([
            'title' => $data['title'],
            'image_path' => $imagePath,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.galleries.index')
            ->with('status', 'Galeri berhasil dibuat.');
    }

    public function edit(GalleryItem $gallery): View
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, GalleryItem $gallery): RedirectResponse
    {
        // Validasi (wajib gambar jika belum ada).
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => [
                Rule::requiredIf(!$gallery->image_path),
                ...UploadValidation::imageRules(),
            ],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $gallery->image_path = UploadValidation::storeUploadedFile($request->file('image'), 'galleries', 'public');
        }

        $gallery->fill([
            'title' => $data['title'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
        $gallery->save();

        return redirect()->route('admin.galleries.index')
            ->with('status', 'Galeri berhasil diperbarui.');
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('status', 'Galeri berhasil dihapus.');
    }
}
