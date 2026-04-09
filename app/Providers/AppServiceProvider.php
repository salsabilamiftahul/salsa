<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        $logoPath = null;
        $logoUrl = null;
        if (!app()->runningInConsole() && Schema::hasTable('settings')) {
            $logoPath = Setting::query()
                ->where('key', 'logo_path')
                ->value('value');

            if ($logoPath) {
                $logoUrl = asset(
                    Str::startsWith($logoPath, 'branding/')
                        ? $logoPath
                        : 'storage/' . $logoPath
                );
            }
        }

        View::share('logoPath', $logoPath);
        View::share('logoUrl', $logoUrl);
    }
}
