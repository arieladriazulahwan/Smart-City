<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; }
        .container { padding:30px; }
        .card { background:white; padding:24px; border-radius:10px; max-width:620px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        label { display:block; font-weight:bold; margin-top:14px; }
        input { width:100%; box-sizing:border-box; padding:12px; border:1px solid #cbd5e1; border-radius:8px; margin-top:7px; }
        button, a { display:inline-block; margin-top:18px; padding:11px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; }
        button { background:#0f766e; color:white; }
        a { background:#64748b; color:white; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Tambah Kategori'])
<div class="container">
    <div class="card">
        @if($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="/admin/kategori/store">
            @csrf
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required>
            <button type="submit">Simpan</button>
            <a href="/admin/kategori">Kembali</a>
        </form>
    </div>
</div>
</body>
</html>
