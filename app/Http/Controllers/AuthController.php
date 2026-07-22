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

        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'id_pendaftar';

        $credentials = [
            $loginField => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.',
                ])->onlyInput('login');
            }

            $request->session()->regenerate();
            Alert::success('Login Berhasil', 'Selamat datang di Dashboard')
                ->toToast()
                ->autoClose(3000);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Login gagal, silakan periksa kembali email/ID pendaftar dan password Anda.',
        ])->onlyInput('login');
    }
}
