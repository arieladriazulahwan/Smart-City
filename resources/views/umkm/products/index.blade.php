<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk UMKM</title>
    <style>
        body { margin:0; font-family:Arial; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; }
        .card { background:white; padding:25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        .btn { padding:10px 14px; border-radius:7px; text-decoration:none; border:none; cursor:pointer; font-weight:bold; }
        .btn-add { background:#0f766e; color:white; }
        .btn-edit { background:#f59e0b; color:white; }
        .btn-delete { background:#dc2626; color:white; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#f1f5f9; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Kelola Produk UMKM'])

<div class="container">
    <div class="card">
        <h1>Kelola Produk UMKM</h1>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <a href="/umkm/produk/create" class="btn btn-add">+ Tambah Produk</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok Manual</th>
                    <th>Stok IoT</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ $product->nama_kategori }}</td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->stok_manual }}</td>
                        <td>{{ $product->stok_iot }}%</td>
                        <td>{{ $product->status_stok }}</td>
                        <td>
                            <a href="/umkm/produk/{{ $product->id }}/edit" class="btn btn-edit">Edit</a>

                            <form method="POST" action="/umkm/produk/{{ $product->id }}/delete" style="display:inline;">
                                @csrf
                                <button class="btn btn-delete" onclick="return confirm('Hapus produk ini?')">Hapus</button>
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
