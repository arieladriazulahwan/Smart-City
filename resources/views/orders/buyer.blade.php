<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <style>
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }

        .navbar {
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-weight: 600;
            transition: opacity .2s ease;
        }

        .navbar a:hover {
            opacity: 0.8;
        }

        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            margin: 0 0 8px;
            color: #1f2937;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .success {
            background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
            color: #166534;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid #10b981;
            font-size: 14px;
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 60px 30px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .empty-state h2 {
            color: #6b7280;
            margin: 0 0 10px;
            font-size: 20px;
        }

        .empty-state p {
            color: #9ca3af;
            margin: 0 0 20px;
        }

        .empty-state a {
            display: inline-block;
            padding: 11px 20px;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .empty-state a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 118, 110, 0.3);
        }

        .orders-grid {
            display: grid;
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #0f766e;
            transition: all .2s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .order-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 16px;
        }

        .order-id {
            color: #0f766e;
            font-weight: 600;
        }

        .order-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin: 16px 0;
            padding: 16px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .meta-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .meta-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
        }

        .order-items {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }

        .item-name {
            color: #1f2937;
            font-weight: 500;
        }

        .item-qty {
            color: #6b7280;
            font-size: 13px;
        }

        .item-price {
            color: #0f766e;
            font-weight: 600;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .order-total {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .total-label {
            color: #6b7280;
            font-size: 12px;
        }

        .total-amount {
            color: #0f766e;
            font-size: 20px;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .order-header {
                flex-direction: column;
                gap: 10px;
            }

            .order-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .order-total {
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Pesanan Saya'])

<div class="container">
    <div class="page-header">
        <h1>📦 Pesanan Saya</h1>
        <p>Pantau status pesanan dan riwayat belanja Anda</p>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <h3>Pesanan <span class="order-id">#{{ $order->id }}</span></h3>
                <span class="order-status status-{{ strtolower(str_replace(' ', '-', $order->status_order)) }}">
                    {{ $order->status_order }}
                </span>
            </div>

            <div class="order-meta">
                <div class="meta-item">
                    <div class="meta-label">Tanggal Pesanan</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">UMKM</div>
                    <div class="meta-value">{{ $order->nama_umkm }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Produk</div>
                    <div class="meta-value">{{ $order->nama_produk }}</div>
                </div>
            </div>

            <div class="order-items">
                <div class="item-row">
                    <div>
                        <div class="item-name">{{ $order->nama_produk }}</div>
                        <div class="item-qty">Qty: {{ $order->jumlah }}</div>
                    </div>
                    <div class="item-price">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="order-footer">
                <div></div>
                <div class="order-total">
                    <div class="total-label">Total Pesanan</div>
                    <div class="total-amount">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <h2>Belum ada pesanan</h2>
            <p>Anda belum membuat pesanan apapun. Mulai belanja sekarang!</p>
            <a href="/produk">🛒 Jelajahi Produk</a>
        </div>
    @endforelse
</div>

</body>
</html>
