<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }


    public function login(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'username.required' => 'username harus diisi.',
                'password.required' => 'Password harus diisi.',
                'username.numeric' => 'username harus berupa angka.',
            ]
        );

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
                case 'Sekretaris':
                    return redirect()->route('sekretaris.dashboard');
                case 'Kepala_Inspektorat':
                    return redirect()->route('kepala.dashboard');
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
    public function register(Request $request)
    {
        $validated = $request->validate(
            [
                'nama_lengkap' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'nik' => 'required|digits:16|unique:users,nik',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'required|string|max:20',
                'kelurahan' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'kabupaten' => 'required|string|max:100',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                'nama_lengkap.required' => 'Nama lengkap harus diisi.',
                'username.required' => 'Username harus diisi.',
                'username.unique' => 'Username sudah terdaftar.',
                'nik.required' => 'NIK harus diisi.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'email.required' => 'Email harus diisi.',
                'email.unique' => 'Email sudah terdaftar.',
                'no_telepon.required' => 'No Telepon harus diisi.',
                'no_telepon.max' => 'No Telepon maksimal 20 karakter.',
                'kelurahan.required' => 'Kelurahan harus diisi.',
                'kecamatan.required' => 'Kecamatan harus diisi.',
                'kabupaten.required' => 'Kabupaten harus diisi.',
                'password.required' => 'Password harus diisi.',
                'password.confirmed' => 'Password tidak cocok.',
                'password.min' => 'Password minimal 6 karakter.',
            ]
        );

        $alamat = 'Kelurahan ' . $validated['kelurahan'] .
            ', Kecamatan ' . $validated['kecamatan'] .
            ', Kabupaten ' . $validated['kabupaten'];

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'nik' => $validated['nik'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'alamat' => $alamat,
            'password' => Hash::make($validated['password']),
            'role' => 'Warga',
            'is_active' => true,
        ]);

        auth()->login($user);

        return redirect()->route('warga.dashboard')->with('success', 'Registrasi berhasil!');
    }
}
