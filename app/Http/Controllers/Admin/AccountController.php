<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        // Data akun admin untuk form.
        return view('admin.account', [
            'adminUsername' => optional($request->user())->username,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Validasi username + password opsional.
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->user()?->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();
        if ($user) {
            $user->username = $data['username'];
            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();
        }

        return redirect()->route('admin.account.edit')
            ->with('status', 'Akun berhasil diperbarui.');
    }
}
