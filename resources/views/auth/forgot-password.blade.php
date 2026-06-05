<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - UMKM Digital Palu</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; color:#111827; }
        .wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px; }
        .box { width:450px; background:white; padding:28px; border-radius:12px; box-shadow:0 8px 22px rgba(0,0,0,.12); }
        h1 { margin:0 0 6px; color:#0f766e; text-align:center; }
        p { text-align:center; color:#64748b; margin:0 0 22px; }
        label { display:block; font-weight:bold; margin-top:13px; }
        input { width:100%; box-sizing:border-box; padding:11px; border:1px solid #cbd5e1; border-radius:8px; margin-top:6px; }
        button, a { display:inline-block; padding:11px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; margin-top:18px; }
        button { background:#0f766e; color:white; cursor:pointer; width:100%; }
        .success { background:#d1fae5; color:#065f46; padding:10px; border-radius:8px; margin-bottom:12px; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:12px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="box">
        <h1>Lupa Password?</h1>
        <p>Masukkan email akun Anda untuk menerima link reset password.</p>

        @if($message = session('success'))
            <div class="success">{{ $message }}</div>
        @endif

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/forgot-password">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <button type="submit">Kirim Link Reset</button>
        </form>

        <p style="text-align:center; margin-top:15px;">
            <a href="/login" style="color:#0f766e; text-decoration:none;">Kembali ke Login</a>
        </p>
    </div>
</div>
</body>
</html>
