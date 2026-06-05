<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Dalam praktik, kirim email dengan link reset
        // Untuk demo, tampilkan token di session
        return back()->with('success', 'Link reset password telah dikirim ke email Anda (untuk demo: ' . substr($token, 0, 8) . '...)');
    }

    public function showReset($token)
    {
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

        abort_if(!$reset || now()->diffInMinutes($reset->created_at) > 60, 404);

        return view('auth.reset-password', ['token' => $token, 'email' => $reset->email]);
    }

    public function processReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        abort_if(!$reset || now()->diffInMinutes($reset->created_at) > 60, 404);

        DB::table('users')->where('email', $request->email)->update([
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Verifikasi otomatis setelah reset
            'updated_at' => now(),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru.');
    }
}
