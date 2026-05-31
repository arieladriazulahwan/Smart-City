<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring UMKM</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; }
        .card { background:white; padding:24px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
        th { background:#f1f5f9; }
        .btn { display:inline-block; padding:9px 13px; border-radius:7px; text-decoration:none; border:0; font-weight:bold; cursor:pointer; margin:2px; }
        .add { background:#0f766e; color:white; }
        .edit { background:#f59e0b; color:white; }
        .verify { background:#2563eb; color:white; }
        .delete { background:#dc2626; color:white; }
        .badge { display:inline-block; padding:5px 9px; border-radius:999px; font-size:12px; font-weight:bold; }
        .pending { background:#fef3c7; color:#92400e; }
        .verified { background:#dcfce7; color:#166534; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Monitoring UMKM'])
<div class="container">
    <div class="card">
        <h1>Monitoring dan Verifikasi UMKM</h1>
        @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
        @if(in_array(auth()->user()->role, ['admin', 'umkm']))
            <a class="btn add" href="/umkm/create">+ Tambah UMKM</a>
        @endif
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Nama UMKM</th><th>Pemilik</th><th>Alamat</th><th>Kategori</th><th>Produk</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($umkms as $index => $umkm)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $umkm->nama_umkm }}</td>
                        <td>{{ $umkm->name }}<br>{{ $umkm->email }}</td>
                        <td>{{ $umkm->alamat }}<br>{{ $umkm->latitude }}, {{ $umkm->longitude }}</td>
                        <td>{{ $umkm->kategori_usaha ?? '-' }}</td>
                        <td>{{ $umkm->total_produk }}</td>
                        <td><span class="badge {{ $umkm->status_verifikasi }}">{{ $umkm->status_verifikasi }}</span></td>
                        <td>
                            <a class="btn edit" href="/umkm/{{ $umkm->id }}/edit">Edit</a>
                            @if(auth()->user()->role === 'admin' && $umkm->status_verifikasi !== 'verified')
                                <form method="POST" action="/umkm/{{ $umkm->id }}/verify" style="display:inline;">@csrf <button class="btn verify">Verifikasi</button></form>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <form method="POST" action="/umkm/{{ $umkm->id }}/delete" style="display:inline;">@csrf <button class="btn delete" onclick="return confirm('Hapus UMKM ini?')">Hapus</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Belum ada data UMKM.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
