<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; }
        .card { background:white; padding:24px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f1f5f9; }
        .success { background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px; }
        .badge { display:inline-block; padding:5px 9px; border-radius:999px; background:#e0f2fe; color:#075985; font-weight:bold; font-size:12px; }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Pesanan Saya'])
<div class="container">
    <div class="card">
        @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
        <h1>Riwayat Pesanan</h1>
        <table>
            <thead>
                <tr>
                    <th>No Pesanan</th><th>Tanggal</th><th>UMKM</th><th>Produk</th><th>Jumlah</th><th>Subtotal</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->tanggal_order }}</td>
                        <td>{{ $order->nama_umkm }}</td>
                        <td>{{ $order->nama_produk }}</td>
                        <td>{{ $order->jumlah }}</td>
                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td><span class="badge">{{ $order->status_order }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
