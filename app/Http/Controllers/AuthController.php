<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($request->input('login'));
        $waInput = str_starts_with($input, '08') ? '628' . substr($input, 2) : $input;

        $user = \App\Models\User::query()
            ->where('email', $input)
            ->orWhere('id_pendaftar', $input)
            ->orWhere('nim', $input)
            ->orWhere('nup', $input)
            ->orWhere('no_wa', $input)
            ->orWhere('no_wa', $waInput)
            ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            if (!$user->is_active) {
                return back()->withErrors([
                    'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.',
                ])->onlyInput('login');
            }

            Auth::login($user);
            $request->session()->regenerate();
            Alert::success('Login Berhasil', 'Selamat datang di Dashboard')
                ->toToast()
                ->autoClose(3000);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Login gagal, silakan periksa kembali Email / NIM / NUP / ID Pendaftar / No. WhatsApp dan Password Anda.',
        ])->onlyInput('login');
    }
}
