<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login & Pengalihan Multi-Role
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek role pengguna untuk diarahkan ke dashboard masing-masing
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Menampilkan halaman Registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Registrasi Mahasiswa Baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'ends_with:pcr.ac.id', // Wajib menggunakan domain resmi pcr.ac.id
            ],
            'no_whatsapp' => 'required|numeric|digits_between:10,15',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.ends_with' => 'Pendaftaran wajib menggunakan email resmi kampus dengan domain pcr.ac.id.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan langsung login.',
            'no_whatsapp.numeric' => 'Nomor WhatsApp harus berupa angka (contoh: 6281234567890).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk menggunakan akun Anda.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}