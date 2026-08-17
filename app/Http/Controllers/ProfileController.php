<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('pages.profile.index', compact('user'));
    }

    /**
     * Update the logged-in user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'no_wa' => [
                'nullable',
                'string',
                'max:25',
                Rule::unique('users')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_wa.unique' => 'Nomor WhatsApp sudah digunakan.',
        ]);

        $user->update($validated);

        Alert::success('Profile berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('profile.index');
    }

    /**
     * Show the password change page.
     */
    public function editPassword()
    {
        return view('pages.profile.password');
    }

    /**
     * Update the logged-in user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru harus minimal 8 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        Alert::success('Password berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('profile.index');
    }
}
