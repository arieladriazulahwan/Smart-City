<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk UMKM</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #111827;
            background: #eef2f7;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 26px 0 40px;
        }

        .hero {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
            margin-bottom: 18px;
            padding: 24px;
            border-radius: 8px;
            color: white;
            background: linear-gradient(135deg, #0f766e 0%, #2563eb 72%);
            box-shadow: 0 16px 32px rgba(15, 23, 42, .14);
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .hero p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.5;
        }

        .hero a {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 10px 14px;
            border-radius: 8px;
            color: white;
            background: rgba(255,255,255,.16);
            text-decoration: none;
            font-weight: 800;
            white-space: nowrap;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
        }

        .stat {
            border-top: 4px solid #0f766e;
        }

        .stat.blue {
            border-top-color: #2563eb;
        }

        .stat.amber {
            border-top-color: #f59e0b;
        }

        .stat.rose {
            border-top-color: #e11d48;
        }

        .label {
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .value {
            margin-top: 8px;
            color: #0f766e;
            font-size: 28px;
            line-height: 1.15;
            font-weight: 900;
        }

        .note {
            margin-top: 8px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 820px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            color: #475569;
            background: #f8fafc;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .order-id {
            color: #0f766e;
            font-weight: 900;
        }

        .badge {
            display: inline-flex;
            width: fit-content;
            border-radius: 999px;
            padding: 5px 9px;
            color: #1e40af;
            background: #dbeafe;
            font-size: 12px;
            font-weight: 800;
        }

        .badge.pending {
            color: #92400e;
            background: #fef3c7;
        }

        .status-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        select {
            min-width: 132px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #111827;
            background: white;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
        }

        button {
            border: 0;
            border-radius: 8px;
            padding: 9px 12px;
            color: white;
            background: #0f766e;
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
        }

        .success {
            margin-bottom: 18px;
            padding: 13px 14px;
            border-radius: 8px;
            color: #166534;
            background: #dcfce7;
            border-left: 4px solid #10b981;
        }

        .empty {
            padding: 28px;
            border-radius: 8px;
            color: #64748b;
            background: #f8fafc;
            text-align: center;
        }

        @media (max-width: 760px) {
            .hero {
                flex-direction: column;
            }

            .status-form {
                align-items: stretch;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Pesanan Masuk UMKM'])

<main class="container">
    <section class="hero">
        <div>
            <h1>Pesanan Masuk</h1>
            <p>Setiap checkout pembeli akan masuk ke UMKM penjual berdasarkan produk yang dibeli.</p>
        </div>
        <a href="/umkm/produk">Kelola Produk</a>
    </section>

    <section class="cards">
        <div class="card stat">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ $ringkasan['total_pesanan'] }}</div>
            <div class="note">Nomor pesanan yang masuk ke toko Anda</div>
        </div>
        <div class="card stat blue">
            <div class="label">Item Terjual</div>
            <div class="value">{{ $ringkasan['total_item'] }}</div>
            <div class="note">Akumulasi jumlah barang dari pembeli</div>
        </div>
        <div class="card stat amber">
            <div class="label">Omzet Pesanan</div>
            <div class="value">Rp {{ number_format($ringkasan['total_omzet'], 0, ',', '.') }}</div>
            <div class="note">Subtotal item milik UMKM Anda</div>
        </div>
        <div class="card stat rose">
            <div class="label">Perlu Diproses</div>
            <div class="value">{{ $ringkasan['menunggu_selesai'] }}</div>
            <div class="note">Pesanan berstatus paid</div>
        </div>
    </section>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <section class="card">
        <h2 style="margin: 0 0 14px; font-size: 18px;">Daftar Pesanan dari Pembeli</h2>

        @if($orders->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Order</th>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="order-id">#{{ $order->order_id }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y H:i') }}</td>
                                <td>{{ $order->pembeli }}</td>
                                <td>{{ $order->nama_produk }}</td>
                                <td>{{ $order->jumlah }}</td>
                                <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $order->status_order === 'paid' ? 'pending' : '' }}">
                                        {{ ucfirst($order->status_order) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="/umkm/transaksi/{{ $order->order_id }}/status" class="status-form">
                                        @csrf
                                        <select name="status_order" required>
                                            <option value="paid" {{ $order->status_order === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="completed" {{ $order->status_order === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty">Belum ada pesanan yang masuk ke UMKM Anda.</div>
        @endif
    </section>
</main>

</body>
</html>
