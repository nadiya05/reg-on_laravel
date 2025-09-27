<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.masuk'); // ✅ view disamakan dengan blade
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // pastikan hanya admin yang bisa login ke web
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'name' => 'Akses ditolak, hanya admin yang bisa login.',
                ]);
            }

            // redirect ke beranda/dashboard
            return redirect()->route('beranda')->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'name' => 'Username atau password salah.',
        ])->onlyInput('name');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('masuk'); // ✅ balik ke halaman login
    }
}
