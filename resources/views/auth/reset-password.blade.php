<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UMKM Digital Palu</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; color:#111827; }
        .wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px; }
        .box { width:450px; background:white; padding:28px; border-radius:12px; box-shadow:0 8px 22px rgba(0,0,0,.12); }
        h1 { margin:0 0 6px; color:#0f766e; text-align:center; }
        p { text-align:center; color:#64748b; margin:0 0 22px; }
        label { display:block; font-weight:bold; margin-top:13px; }
        input { width:100%; box-sizing:border-box; padding:11px; border:1px solid #cbd5e1; border-radius:8px; margin-top:6px; }
        button { background:#0f766e; color:white; cursor:pointer; width:100%; padding:11px; border-radius:8px; border:0; font-weight:bold; margin-top:18px; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:12px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="box">
        <h1>Reset Password</h1>
        <p>Masukkan password baru Anda.</p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <label>Email</label>
            <input type="email" value="{{ $email }}" readonly>

            <label>Password Baru</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
