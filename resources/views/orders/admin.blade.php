<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
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
            max-width: 1400px;
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
            margin: 0;
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
            gap: 20px;
        }

        .order-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 16px;
            flex: 1;
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

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .order-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin: 16px 0;
            padding: 16px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .grid-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .item-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .item-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            gap: 16px;
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

        .status-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: border-color .2s ease;
        }

        select:focus {
            outline: none;
            border-color: #0f766e;
        }

        button {
            padding: 8px 16px;
            border: 0;
            border-radius: 6px;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .order-header {
                flex-direction: column;
            }

            .order-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-total {
                align-items: flex-start;
            }

            .status-form {
                flex-direction: column;
                width: 100%;
            }

            select, button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Laporan Transaksi'])

<div class="container">
    <div class="page-header">
        <h1>📊 Laporan Transaksi</h1>
        <p>Monitoring semua transaksi marketplace. Status pesanan diperbarui oleh UMKM penjual.</p>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <h3>Pesanan <span class="order-id">#{{ $order->id }}</span></h3>
                <span class="order-status status-{{ strtolower($order->status_order) }}">
                    {{ ucfirst($order->status_order) }}
                </span>
            </div>

            <div class="order-grid">
                <div class="grid-item">
                    <div class="item-label">Tanggal</div>
                    <div class="item-value">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y') }}</div>
                </div>
                <div class="grid-item">
                    <div class="item-label">Pembeli</div>
                    <div class="item-value">{{ $order->pembeli }}</div>
                </div>
                <div class="grid-item">
                    <div class="item-label">UMKM</div>
                    <div class="item-value">{{ $order->nama_umkm }}</div>
                </div>
                <div class="grid-item">
                    <div class="item-label">Produk</div>
                    <div class="item-value">{{ $order->nama_produk }}</div>
                </div>
                <div class="grid-item">
                    <div class="item-label">Qty</div>
                    <div class="item-value">{{ $order->jumlah }}</div>
                </div>
            </div>

            <div class="order-footer">
                <div class="order-total">
                    <div class="total-label">Total Transaksi</div>
                    <div class="total-amount">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>

                @if(false)
                    <form method="POST" action="/admin/transaksi/{{ $order->id }}/status" class="status-form">
                        @csrf
                        <select name="status_order" required>
                            <option value="pending" {{ $order->status_order === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="paid" {{ $order->status_order === 'paid' ? 'selected' : '' }}>✓ Paid</option>
                            <option value="completed" {{ $order->status_order === 'completed' ? 'selected' : '' }}>✓✓ Completed</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <h2>Belum ada transaksi</h2>
            <p>Sistem marketplace sedang menunggu transaksi pertama.</p>
        </div>
    @endforelse
</div>

</body>
</html>
