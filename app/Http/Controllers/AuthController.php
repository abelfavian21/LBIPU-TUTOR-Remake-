<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $remember = $request->boolean('remember');

        // Boleh login pakai username ATAU NIM tutor (mis. L200250035)
        $ok = Auth::attempt(['username' => $data['username'], 'password' => $data['password'], 'status' => 'aktif'], $remember)
            || Auth::attempt(['nim' => strtoupper($data['username']), 'password' => $data['password'], 'status' => 'aktif'], $remember);

        if (! $ok) {
            throw ValidationException::withMessages([
                'username' => 'Username/NIM atau password salah, atau akun tidak aktif.',
            ]);
        }

        $request->session()->regenerate();

        // Setelah login langsung ke dashboard
        return redirect()->route('dashboard');
    }

    public function cas(): RedirectResponse
    {
        return redirect()->route('login')->with('info', 'Login CAS UMS belum dikonfigurasi. Silakan gunakan username & password.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
