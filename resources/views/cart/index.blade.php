<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; max-width:1200px; margin:0 auto; }
        .progress { display:flex; gap:20px; margin-bottom:25px; justify-content:center; }
        .progress-step { display:flex; flex-direction:column; align-items:center; }
        .progress-dot { width:40px; height:40px; border-radius:50%; background:#0f766e; color:white; display:flex; align-items:center; justify-content:center; font-weight:bold; margin-bottom:8px; }
        .progress-step.active .progress-dot { background:#10b981; box-shadow:0 0 0 8px rgba(16,185,129,.1); }
        .progress-label { font-size:13px; color:#666; font-weight:600; }
        .grid { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
        .card { background:white; padding:24px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .summary-card { position:sticky; top:20px; }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { padding:14px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f1f5f9; font-weight:600; }
        tr:hover { background:#f9fafb; }
        input { width:72px; padding:9px; border:1px solid #cbd5e1; border-radius:7px; }
        .btn { padding:10px 14px; border-radius:7px; border:0; text-decoration:none; font-weight:bold; cursor:pointer; transition:.2s ease; }
        .primary { background:#0f766e; color:white; width:100%; }
        .primary:hover { background:#055b54; transform:translateY(-2px); box-shadow:0 4px 12px rgba(15,118,110,.3); }
        .muted { background:#64748b; color:white; }
        .danger { background:#dc2626; color:white; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; border-left:4px solid #10b981; }
        .error { background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:15px; border-left:4px solid #dc2626; }
        .summary-section { margin-bottom:18px; padding-bottom:18px; border-bottom:1px solid #e5e7eb; }
        .summary-section:last-child { border:none; }
        .summary-label { font-size:13px; color:#666; margin-bottom:4px; }
        .summary-value { font-size:18px; font-weight:bold; color:#0f766e; }
        .total-section { background:linear-gradient(135deg, #0f766e 0%, #055b54 100%); color:white; padding:16px; border-radius:10px; margin:15px 0; }
        .total-label { font-size:14px; opacity:.9; }
        .total-value { font-size:28px; font-weight:bold; margin-top:6px; }
        .trust-badge { display:flex; align-items:center; gap:8px; font-size:12px; color:#666; margin-top:12px; padding-top:12px; border-top:1px solid #e5e7eb; }
        .trust-badge::before { content:'✓'; color:#10b981; font-weight:bold; font-size:16px; }
        @media (max-width:900px) { .grid { grid-template-columns:1fr; } .summary-card { position:static; } }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Keranjang Belanja'])

<div class="container">
    <div class="progress">
        <div class="progress-step active">
            <div class="progress-dot">1</div>
            <div class="progress-label">Keranjang</div>
        </div>
        <div class="progress-step">
            <div class="progress-dot">2</div>
            <div class="progress-label">Checkout</div>
        </div>
        <div class="progress-step">
            <div class="progress-dot">3</div>
            <div class="progress-label">Konfirmasi</div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="error">{{ session('error') }}</div> @endif

            <h1>🛒 Keranjang Belanja Anda</h1>
            <p style="color:#666;">Review dan perbarui jumlah produk sebelum checkout</p>

            @if($products->count())
                <table>
                    <thead><tr><th>Produk</th><th>UMKM</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td><strong>{{ $product->nama_produk }}</strong></td>
                                <td>{{ $product->nama_umkm }}</td>
                                <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                <td>
                                    <form method="POST" action="/keranjang/{{ $product->id }}/update" style="display:flex; gap:6px;">
                                        @csrf
                                        <input type="number" name="jumlah" value="{{ $product->jumlah }}" min="1">
                                        <button class="btn muted" style="width:auto;">Update</button>
                                    </form>
                                </td>
                                <td><strong>Rp {{ number_format($product->subtotal, 0, ',', '.') }}</strong></td>
                                <td>
                                    <form method="POST" action="/keranjang/{{ $product->id }}/remove">
                                        @csrf
                                        <button class="btn danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding:40px; text-align:center; color:#999;">
                    <p style="font-size:18px; margin:0;">Keranjang masih kosong</p>
                    <a href="/produk" class="btn primary" style="margin-top:15px; display:inline-block; width:auto; padding:10px 20px;">Belanja Sekarang</a>
                </div>
            @endif
        </div>

        <div class="card summary-card">
            <h3 style="margin-top:0;">📋 Ringkasan Pesanan</h3>

            @if($products->count())
                <div class="summary-section">
                    <div class="summary-label">Jumlah Item</div>
                    <div class="summary-value">{{ array_sum(session('cart', [])) }} produk</div>
                </div>

                <div class="summary-section">
                    <div class="summary-label">Subtotal</div>
                    <div class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

                <div class="summary-section">
                    <div class="summary-label">Ongkos Kirim</div>
                    <div class="summary-value" style="color:#10b981;">Gratis ✓</div>
                </div>

                <div class="total-section">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

                <form method="POST" action="/checkout">
                    @csrf
                    <button class="btn primary" onclick="return confirm('Lanjut checkout pesanan?')">Lanjut Checkout →</button>
                </form>

                <div class="trust-badge">Transaksi aman terjamin</div>
            @else
                <p style="color:#999; text-align:center; padding:20px 0;">Tambahkan produk untuk checkout</p>
            @endif
        </div>
    </div>
</div>
</body>
</html>
