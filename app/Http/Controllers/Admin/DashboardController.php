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
        // Helper: menentukan status aktif berdasarkan jadwal dan flag.
        $isCurrentlyActive = function ($model): bool {
            $now = now();
            $startsAt = $model->starts_at ?? null;
            $endsAt = $model->ends_at ?? null;

            if (!$model->is_active) {
                return false;
            }

            if ($startsAt && $startsAt->gt($now)) {
                return false;
            }

            if ($endsAt && $endsAt->lt($now)) {
                return false;
            }

            return true;
        };

        // Gabungkan data terbaru dari agenda, galeri, dan konten utama.
        $recentContents = collect()
            ->merge(
                Agenda::query()->latest()->limit(5)->get()->map(fn (Agenda $agenda) => [
                    'type' => 'Agenda',
                    'title' => $agenda->title,
                    'created_at' => $agenda->created_at,
                    'is_active' => $isCurrentlyActive($agenda),
                    'edit_url' => route('admin.agendas.edit', $agenda),
                    'delete_url' => route('admin.agendas.destroy', $agenda),
                ])
            )
            ->merge(
                GalleryItem::query()->latest()->limit(5)->get()->map(fn (GalleryItem $gallery) => [
                    'type' => 'Galeri Foto',
                    'title' => $gallery->title,
                    'created_at' => $gallery->created_at,
                    'is_active' => $isCurrentlyActive($gallery),
                    'edit_url' => route('admin.galleries.edit', $gallery),
                    'delete_url' => route('admin.galleries.destroy', $gallery),
                ])
            )
            ->merge(
                MainContent::query()->latest()->limit(5)->get()->map(fn (MainContent $content) => [
                    'type' => 'Konten',
                    'title' => $content->title,
                    'created_at' => $content->created_at,
                    'is_active' => $isCurrentlyActive($content),
                    'edit_url' => route('admin.main-contents.edit', $content),
                    'delete_url' => route('admin.main-contents.destroy', $content),
                ])
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
