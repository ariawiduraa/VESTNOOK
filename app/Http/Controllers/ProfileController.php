<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.settings', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [];

        // ── Nama ──
        if ($request->filled('name')) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        // ── Avatar ──
        if ($request->hasFile('avatar')) {
            $rules['avatar'] = ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        // ── Password ──
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $rules['current_password'] = ['required'];
            $rules['new_password'] = [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ];
        }

        $request->validate($rules, [
            'new_password.regex'     => 'Password harus mengandung minimal 1 huruf besar, 1 angka, dan 1 simbol.',
            'new_password.min'       => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Proses nama
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        // Proses avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                $oldPath = storage_path('app/public/' . $user->avatar);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Proses password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
