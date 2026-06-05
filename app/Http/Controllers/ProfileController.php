<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $umkm = null;

        if ($user->role === 'umkm') {
            $umkm = DB::table('umkms')->where('user_id', $user->id)->first();
        }

        return view('profile.show', compact('user', 'umkm'));
    }

    public function edit()
    {
        $user = Auth::user();
        $umkm = null;

        if ($user->role === 'umkm') {
            $umkm = DB::table('umkms')->where('user_id', $user->id)->first();
        }

        return view('profile.edit', compact('user', 'umkm'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return redirect('/profile')->with('success', 'Password berhasil diperbarui.');
    }
}
