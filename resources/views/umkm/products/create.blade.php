<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <style>
        * { box-sizing:border-box; }
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; color:#111827; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar h2 { margin:0; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; display:flex; justify-content:center; }
        .card { width:100%; max-width:760px; background:white; padding:26px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        h1 { margin:0 0 18px; }
        label { font-weight:bold; display:block; margin-top:15px; }
        input, select, textarea { width:100%; padding:12px; margin-top:7px; border:1px solid #cbd5e1; border-radius:8px; font:inherit; }
        textarea { resize:vertical; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:20px; }
        button, a.btn { display:inline-block; padding:11px 16px; border-radius:8px; text-decoration:none; border:none; font-weight:bold; cursor:pointer; }
        button { background:#0f766e; color:white; }
        a.btn { background:#64748b; color:white; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:15px; }
        @media (max-width: 640px) {
            .navbar { display:block; padding:16px 20px; }
            .navbar a { display:inline-block; margin:10px 12px 0 0; }
            .container { padding:18px; }
            .card { padding:20px; }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Tambah Produk UMKM'])

<div class="container">
    <div class="card">
        <h1>Form Tambah Produk</h1>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/umkm/produk/store">
            @csrf

            <label>UMKM</label>
            <select name="umkm_id" required>
                @foreach($umkms as $umkm)
                    <option value="{{ $umkm->id }}">{{ $umkm->nama_umkm }}</option>
                @endforeach
            </select>

            <label>Kategori</label>
            <select name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                @endforeach
            </select>

            <label>Nama Produk</label>
            <input type="text" name="nama_produk" required>

            <label>Harga</label>
            <input type="number" name="harga" required>

            <label>Stok Manual</label>
            <input type="number" name="stok_manual" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="4"></textarea>

            <div class="actions">
                <button type="submit">Simpan Produk</button>
                <a class="btn" href="/umkm/produk">Kembali</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
