<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MainContentController;
use App\Http\Controllers\Admin\PreviewDisplayController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tv', [DisplayController::class, 'index'])->name('display');
Route::get('/display', [DisplayController::class, 'index']);
Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/preview-display', [PreviewDisplayController::class, 'edit'])->name('preview.edit');
    Route::put('/preview-display', [PreviewDisplayController::class, 'update'])->name('preview.update');
    Route::resource('agendas', AgendaController::class)->except(['show']);
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::resource('main-contents', MainContentController::class)->except(['show']);
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
});

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
