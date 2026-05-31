<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; }
        .card { background:white; padding:24px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f1f5f9; }
        .btn { padding:9px 13px; border-radius:7px; text-decoration:none; border:0; font-weight:bold; cursor:pointer; }
        .add { background:#0f766e; color:white; }
        .edit { background:#f59e0b; color:white; }
        .delete { background:#dc2626; color:white; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Kelola Kategori'])
<div class="container">
    <div class="card">
        <h1>Kelola Kategori Produk</h1>
        @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
        <a class="btn add" href="/admin/kategori/create">+ Tambah Kategori</a>
        <table>
            <thead><tr><th>No</th><th>Kategori</th><th>Total Produk</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $category->nama_kategori }}</td>
                        <td>{{ $category->total_produk }}</td>
                        <td>
                            <a class="btn edit" href="/admin/kategori/{{ $category->id }}/edit">Edit</a>
                            <form method="POST" action="/admin/kategori/{{ $category->id }}/delete" style="display:inline;">
                                @csrf
                                <button class="btn delete" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
