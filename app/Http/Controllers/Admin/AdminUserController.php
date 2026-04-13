<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $admins = User::query()
            ->where('is_admin', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.admins.index', [
            'admins' => $admins,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::query()->create([
            'name' => trim($data['name']),
            'username' => trim($data['username']),
            'password' => $data['password'],
            'is_admin' => true,
            'is_super_admin' => false,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('status', 'Akun admin berhasil ditambahkan.');
    }

    public function edit(User $admin): View
    {
        $admin = $this->resolveAdmin($admin);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $admin = $this->resolveAdmin($admin);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($admin->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $admin->name = trim($data['name']);
        $admin->username = trim($data['username']);
        $admin->is_admin = true;

        if (!empty($data['password'])) {
            $admin->password = $data['password'];
        }

        $admin->save();

        return redirect()->route('admin.admins.index')
            ->with('status', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(Request $request, User $admin): RedirectResponse
    {
        $admin = $this->resolveAdmin($admin);

        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Akun super admin tidak dapat dihapus.');
        }

        if ((int) $request->user()?->id === (int) $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Akun admin yang sedang digunakan tidak bisa dihapus.');
        }

        $remainingAdmins = User::query()
            ->where('is_admin', true)
            ->whereKeyNot($admin->id)
            ->count();

        if ($remainingAdmins < 1) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Minimal harus ada satu akun admin aktif.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('status', 'Akun admin berhasil dihapus.');
    }

    private function resolveAdmin(User $admin): User
    {
        abort_unless($admin->is_admin, 404);

        return $admin;
    }
}
