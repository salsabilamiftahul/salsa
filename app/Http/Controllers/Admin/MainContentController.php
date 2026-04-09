<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MainContentController extends Controller
{
    public function index(Request $request): View
    {
        // Filter pencarian dan status.
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $status = trim((string) $request->query('status', ''));
        $media = trim((string) $request->query('media', ''));
        $schedule = trim((string) $request->query('schedule', ''));
        $now = now();

        $query = MainContent::query()->latest();
        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }
        if (in_array($category, ['kegiatan', 'spp', 'sop', 'video', 'lain-lain'], true)) {
            $query->where('category', $category);
        }
        if (in_array($media, ['video', 'image'], true)) {
            $query->where('media_type', $media);
        }

        // Filter berdasarkan jadwal.
        if ($schedule === 'upcoming') {
            $query->whereNotNull('starts_at')->where('starts_at', '>', $now);
        } elseif ($schedule === 'ongoing') {
            $query->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
        } elseif ($schedule === 'ended') {
            $query->whereNotNull('ends_at')->where('ends_at', '<', $now);
        } elseif ($schedule === 'unscheduled') {
            $query->whereNull('starts_at')->whereNull('ends_at');
        }

        // Filter berdasarkan status aktif/nonaktif.
        if ($status === 'active') {
            $query->where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                });
        } elseif ($status === 'inactive') {
            $query->where(function ($q) use ($now) {
                $q->where('is_active', false)
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('is_active', true)
                            ->where(function ($q3) use ($now) {
                                $q3->where(function ($q4) use ($now) {
                                    $q4->whereNotNull('starts_at')->where('starts_at', '>', $now);
                                })->orWhere(function ($q4) use ($now) {
                                    $q4->whereNotNull('ends_at')->where('ends_at', '<', $now);
                                });
                            });
                    });
            });
        }

        return view('admin.main-contents.index', [
            'contents' => $query->paginate(10)->appends([
                'q' => $search,
                'category' => $category,
                'status' => $status,
                'media' => $media,
                'schedule' => $schedule,
            ]),
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'media' => $media,
            'schedule' => $schedule,
        ]);
    }

    public function create(): View
    {
        return view('admin.main-contents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi input konten utama.
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:kegiatan,spp,sop,video,lain-lain'],
            'media' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/ogg,video/quicktime', 'max:512000'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $mediaPath = null;
        $mediaType = 'image';
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaPath = $file->store('main-contents', 'public');
            $mimeType = (string) $file->getMimeType();
            $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';
        }

        MainContent::query()->create([
            'title' => $data['title'],
            'image_path' => $mediaPath,
            'media_type' => $mediaType,
            'category' => $data['category'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.main-contents.index')
            ->with('status', 'Konten utama berhasil dibuat.');
    }

    public function edit(MainContent $mainContent): View
    {
        return view('admin.main-contents.edit', [
            'mainContent' => $mainContent,
        ]);
    }

    public function update(Request $request, MainContent $mainContent): RedirectResponse
    {
        // Media wajib jika belum ada file yang tersimpan.
        $hasExistingMedia = (bool) ($mainContent->image_path);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:kegiatan,spp,sop,video,lain-lain'],
            'media' => [
                Rule::requiredIf(!$hasExistingMedia),
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/ogg,video/quicktime',
                'max:512000',
            ],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mainContent->image_path = $file->store('main-contents', 'public');
            $mimeType = (string) $file->getMimeType();
            $mainContent->media_type = str_starts_with($mimeType, 'video/') ? 'video' : 'image';
        }

        $mainContent->fill([
            'title' => $data['title'],
            'category' => $data['category'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
        $mainContent->save();

        return redirect()->route('admin.main-contents.index')
            ->with('status', 'Konten utama berhasil diperbarui.');
    }

    public function destroy(MainContent $mainContent): RedirectResponse
    {
        $mainContent->delete();

        return redirect()->route('admin.main-contents.index')
            ->with('status', 'Konten utama berhasil dihapus.');
    }
}
