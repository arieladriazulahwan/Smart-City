<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function register(Request $request)
    {
        $userId = null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pembeli,umkm',
            'nama_umkm' => 'required_if:role,umkm|nullable|string|max:255',
            'alamat' => 'required_if:role,umkm|nullable|string',
            'kategori_usaha' => 'nullable|string|max:255',
            'latitude' => 'required_if:role,umkm|nullable|numeric',
            'longitude' => 'required_if:role,umkm|nullable|numeric',
        ]);

        DB::transaction(function () use ($request, &$userId) {
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->role === 'umkm') {
                DB::table('umkms')->insert([
                    'user_id' => $userId,
                    'nama_umkm' => $request->nama_umkm,
                    'alamat' => $request->alamat,
                    'kategori_usaha' => $request->kategori_usaha,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'status_verifikasi' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        Auth::loginUsingId($userId);

        return redirect('/dashboard')->with('success', 'Registrasi berhasil.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($role === 'umkm') {
                return redirect('/umkm/dashboard');
            }

            if ($role === 'pemerintah') {
                return redirect('/pemerintah/dashboard');
            }

            return redirect('/produk');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
