<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return redirect()->route('login')->with('status', 'Silakan login sebagai admin.');
        }

        if (!Schema::hasColumn('users', 'is_super_admin')) {
            abort(500, 'Kolom super admin belum tersedia. Jalankan migrasi terbaru.');
        }

        if (
            User::query()->where('is_admin', true)->where('is_super_admin', true)->doesntExist()
            && !$user->isSuperAdmin()
        ) {
            $user->forceFill(['is_super_admin' => true])->save();
            $user->refresh();
        }

        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses super admin diperlukan.');
        }

        return $next($request);
    }
}
