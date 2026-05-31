<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; }
        .card { background:white; padding:24px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f1f5f9; }
        input { width:72px; padding:9px; border:1px solid #cbd5e1; border-radius:7px; }
        .btn { padding:9px 13px; border-radius:7px; border:0; text-decoration:none; font-weight:bold; cursor:pointer; }
        .primary { background:#0f766e; color:white; }
        .muted { background:#64748b; color:white; }
        .danger { background:#dc2626; color:white; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; }
        .error { background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:15px; }
        .total { font-size:22px; font-weight:bold; color:#0f766e; text-align:right; margin-top:18px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Keranjang Belanja'])
<div class="container">
    <div class="card">
        @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="error">{{ session('error') }}</div> @endif
        <h1>Checkout Produk Lokal</h1>
        <table>
            <thead><tr><th>Produk</th><th>UMKM</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ $product->nama_umkm }}</td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="/keranjang/{{ $product->id }}/update">
                                @csrf
                                <input type="number" name="jumlah" value="{{ $product->jumlah }}" min="1">
                                <button class="btn muted">Update</button>
                            </form>
                        </td>
                        <td>Rp {{ number_format($product->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="/keranjang/{{ $product->id }}/remove">
                                @csrf
                                <button class="btn danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Keranjang masih kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="total">Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
        @if($products->count())
            <form method="POST" action="/checkout" style="text-align:right;">
                @csrf
                <button class="btn primary" onclick="return confirm('Lanjut checkout?')">Checkout</button>
            </form>
        @endif
    </div>
</div>
</body>
</html>
