<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();


            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Akun Anda telah dinonaktifkan.');
            }
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Ketua_Bidang_Investigasi':
                    return redirect()->route('ketua_bidang.dashboard');
                case 'Pegawai':
                    return redirect()->route('pegawai.dashboard');
                case 'Warga':
                    return redirect()->route('warga.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
