<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\GalleryItem;
use App\Models\MainContent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Helper: menentukan status tampil untuk agenda berdasarkan jadwal.
        $agendaStatus = function (Agenda $agenda): array {
            $now = now();
            $startsAt = $agenda->starts_at;
            $endsAt = $agenda->ends_at;

            if ($endsAt && $endsAt->lt($now)) {
                return [
                    'label' => 'Selesai',
                    'class' => 'text-muted',
                ];
            }

            if ($startsAt && $startsAt->gt($now)) {
                return [
                    'label' => 'Terjadwal',
                    'class' => 'text-warning',
                ];
            }

            return [
                'label' => 'Berlangsung',
                'class' => 'text-success',
            ];
        };

        // Helper: menentukan status aktif berdasarkan jadwal dan flag.
        $visibilityStatus = function ($model): array {
            $now = now();
            $startsAt = $model->starts_at ?? null;
            $endsAt = $model->ends_at ?? null;

            $isActive = (bool) ($model->is_active ?? false);

            if ($isActive && $startsAt && $startsAt->gt($now)) {
                $isActive = false;
            }

            if ($isActive && $endsAt && $endsAt->lt($now)) {
                $isActive = false;
            }

            return [
                'label' => $isActive ? 'Aktif' : 'Nonaktif',
                'class' => $isActive ? 'text-success' : 'text-danger',
            ];
        };

        // Gabungkan data terbaru dari agenda, galeri, dan konten utama.
        $recentContents = collect()
            ->merge(
                Agenda::query()->latest()->limit(5)->get()->map(function (Agenda $agenda) use ($agendaStatus) {
                    $status = $agendaStatus($agenda);

                    return [
                        'type' => 'Agenda',
                        'title' => $agenda->title,
                        'created_at' => $agenda->created_at,
                        'status_label' => $status['label'],
                        'status_class' => $status['class'],
                        'edit_url' => route('admin.agendas.edit', $agenda),
                        'delete_url' => route('admin.agendas.destroy', $agenda),
                    ];
                })
            )
            ->merge(
                GalleryItem::query()->latest()->limit(5)->get()->map(function (GalleryItem $gallery) use ($visibilityStatus) {
                    $status = $visibilityStatus($gallery);

                    return [
                        'type' => 'Galeri Foto',
                        'title' => $gallery->title,
                        'created_at' => $gallery->created_at,
                        'status_label' => $status['label'],
                        'status_class' => $status['class'],
                        'edit_url' => route('admin.galleries.edit', $gallery),
                        'delete_url' => route('admin.galleries.destroy', $gallery),
                    ];
                })
            )
            ->merge(
                MainContent::query()->latest()->limit(5)->get()->map(function (MainContent $content) use ($visibilityStatus) {
                    $status = $visibilityStatus($content);

                    return [
                        'type' => 'Konten',
                        'title' => $content->title,
                        'created_at' => $content->created_at,
                        'status_label' => $status['label'],
                        'status_class' => $status['class'],
                        'edit_url' => route('admin.main-contents.edit', $content),
                        'delete_url' => route('admin.main-contents.destroy', $content),
                    ];
                })
            )
            ->sortByDesc('created_at')
            ->values();

        // Paginate manual untuk koleksi gabungan.
        $perPage = 5;
        $currentPage = (int) request()->query('page', 1);
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $paginatedContents = new LengthAwarePaginator(
            $recentContents->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $recentContents->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('admin.dashboard', [
            // Ringkasan jumlah
            'agendaCount' => Agenda::query()->count(),
            'galleryCount' => GalleryItem::query()->count(),
            'mainContentCount' => MainContent::query()->count(),
            // Tabel terbaru
            'recentContents' => $paginatedContents,
        ]);
    }
}
