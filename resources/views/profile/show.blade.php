<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - UMKM Digital Palu</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .container { max-width:800px; margin:0 auto; padding:25px; }
        .card { background:white; padding:22px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.08); margin-bottom:20px; }
        h2 { color:#0f766e; margin-top:0; }
        .info { margin:15px 0; }
        .info label { font-weight:bold; display:block; margin-bottom:5px; color:#555; }
        .info p { margin:0; color:#333; }
        .actions { display:flex; gap:10px; margin-top:15px; }
        a, button { padding:10px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; cursor:pointer; }
        .primary { background:#0f766e; color:white; }
        .secondary { background:#e2e8f0; color:#0f172a; }
        .danger { background:#dc2626; color:white; }
        .success { background:#d1fae5; color:#065f46; padding:10px; border-radius:8px; margin-bottom:12px; }
        .back { margin-bottom:15px; }
        .back a { display:inline-block; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Profil Pengguna'])

<div class="container">
    @if($message = session('success'))
        <div class="success">{{ $message }}</div>
    @endif

    <div class="back">
        <a href="/dashboard" class="secondary">← Kembali ke Dashboard</a>
    </div>

    <div class="card">
        <h2>Informasi Akun</h2>
        <div class="info">
            <label>Nama:</label>
            <p>{{ $user->name }}</p>
        </div>
        <div class="info">
            <label>Email:</label>
            <p>{{ $user->email }}</p>
        </div>
        <div class="info">
            <label>Role:</label>
            <p>
                @if($user->role === 'pembeli') Pembeli
                @elseif($user->role === 'umkm') UMKM
                @elseif($user->role === 'admin') Admin
                @elseif($user->role === 'pemerintah') Pemerintah
                @else {{ $user->role }} @endif
            </p>
        </div>
        <div class="info">
            <label>Status Email:</label>
            <p>{{ $user->email_verified_at ? '✓ Terverifikasi' : '✗ Belum Terverifikasi' }}</p>
        </div>
        <div class="actions">
            <a href="/profile/edit" class="primary">Edit Profil</a>
            <a href="/profile/change-password" class="primary">Ganti Password</a>
        </div>
    </div>

    @if($umkm)
    <div class="card">
        <h2>Data UMKM</h2>
        <div class="info">
            <label>Nama UMKM:</label>
            <p>{{ $umkm->nama_umkm }}</p>
        </div>
        <div class="info">
            <label>Alamat:</label>
            <p>{{ $umkm->alamat }}</p>
        </div>
        <div class="info">
            <label>Kategori Usaha:</label>
            <p>{{ $umkm->kategori_usaha }}</p>
        </div>
        <div class="info">
            <label>Status Verifikasi:</label>
            <p>
                @if($umkm->status_verifikasi === 'verified')
                    <span style="color:#10b981;">✓ Terverifikasi</span>
                @else
                    <span style="color:#f59e0b;">⏳ Menunggu Verifikasi</span>
                @endif
            </p>
        </div>
    </div>
    @endif
</div>
</body>
</html>
