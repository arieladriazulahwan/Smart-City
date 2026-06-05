<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - UMKM Digital Palu</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .container { max-width:600px; margin:0 auto; padding:25px; }
        .card { background:white; padding:22px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        h2 { color:#0f766e; margin-top:0; }
        label { display:block; font-weight:bold; margin-top:13px; }
        input { width:100%; box-sizing:border-box; padding:11px; border:1px solid #cbd5e1; border-radius:8px; margin-top:6px; }
        button, a { padding:11px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; margin-top:18px; cursor:pointer; }
        button { background:#0f766e; color:white; }
        .secondary { background:#e2e8f0; color:#0f172a; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:12px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Ganti Password'])

<div class="container">
    @if($message = session('error'))
        <div class="error">{{ $message }}</div>
    @endif

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <h2>Ganti Password</h2>

        <form method="POST" action="/profile/change-password">
            @csrf

            <label>Password Saat Ini</label>
            <input type="password" name="current_password" required>

            <label>Password Baru</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit">Perbarui Password</button>
                <a href="/profile" class="secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
