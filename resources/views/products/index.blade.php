<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Marketplace Produk - UMKM Digital Palu</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background: #0f766e;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-weight: bold;
        }

        .container {
            padding: 30px;
        }

        .title {
            margin-bottom: 20px;
        }

        .filters {
            background: white;
            padding: 16px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 22px;
            display: grid;
            grid-template-columns: 1fr 220px auto;
            gap: 12px;
        }

        input, select {
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        .hero {
            background: linear-gradient(90deg, #06b6d4 0%, #0f766e 100%);
            color: white;
            padding: 34px;
            border-radius: 12px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .hero h2 { margin:0; font-size:28px; }

        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 10px 30px rgba(2,6,23,0.18);
        }

        .image-box {
            height: 170px;
            background: linear-gradient(135deg, #99f6e4, #14b8a6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 42px;
            font-weight: bold;
            position: relative;
        }

        .ribbon {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #f97316;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            border-radius: 6px;
            font-size: 12px;
        }

        .content {
            padding: 18px;
        }

        .content h3 {
            margin: 0 0 8px;
            color: #111827;
        }

        .price {
            color: #0f766e;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            background: #ccfbf1;
            color: #0f766e;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 10px;
            margin-right: 8px;
        }

        .info {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .stock {
            margin-top: 12px;
            padding: 10px;
            border-radius: 8px;
            background: #f1f5f9;
            font-size: 14px;
        }

        .btn {
            display: block;
            margin-top: 15px;
            background: #0f766e;
            color: white;
            text-align: center;
            padding: 11px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .btn.secondary {
            background: #10b981;
            margin-top:8px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        @media (max-width: 900px) {
            .products, .filters {
                grid-template-columns: 1fr;
            }

            .products { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Marketplace Produk'])

<div class="container">
    <div class="hero">
        <div>
            <h2>Belanja Produk Lokal, Dukung UMKM Palu</h2>
            <p style="opacity:.92; margin-top:6px;">Temukan produk khas kota Palu dengan mudah — gratis ongkir untuk penjual verified.</p>
        </div>
        <div>
            <a href="/produk" class="btn" style="background:white; color:#0f766e;">Jelajahi Produk</a>
        </div>
    </div>
    <div class="title">
        <h1>Marketplace Produk UMKM</h1>
        <p>Produk lokal Kota Palu dalam platform Smart Economy.</p>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="/produk" class="filters">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk atau UMKM">
        <select name="category_id">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->nama_kategori }}
                </option>
            @endforeach
        </select>
        <button class="btn" type="submit" style="margin:0;">Cari</button>
    </form>

    <div class="products">
        @forelse($products as $product)
            <div class="card">
                <div class="image-box">
                    @if($product->stok_manual <= 10)
                        <div class="ribbon">Terbatas</div>
                    @endif
                    {{ strtoupper(substr($product->nama_produk, 0, 1)) }}
                </div>

                <div class="content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                        <div>
                            <span class="badge">{{ $product->nama_kategori }}</span>
                            <h3 style="display:inline-block; margin-left:6px;">{{ $product->nama_produk }}</h3>
                        </div>
                        <div style="text-align:right;">
                            <div class="price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="info" style="margin-top:8px;">
                        <strong>UMKM:</strong> {{ $product->nama_umkm }} <br>
                        <strong>Deskripsi:</strong> {{ 
                            strlen($product->deskripsi) > 120 ? substr($product->deskripsi,0,120) . '...' : $product->deskripsi
                        }}
                    </div>

                    <div class="stock">
                        <strong>Stok:</strong> {{ $product->stok_manual }} • <strong>Status:</strong> {{ $product->status_stok }}
                    </div>

                    <div style="display:flex; gap:8px;">
                        <form method="POST" action="/keranjang/{{ $product->id }}/add" style="flex:1;">
                            @csrf
                            <button class="btn" type="submit">Tambah ke Keranjang</button>
                        </form>

                        <a href="/keranjang" class="btn secondary" style="flex:0 0 140px; display:inline-flex; align-items:center; justify-content:center;">Lihat Keranjang</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Produk tidak ditemukan.</p>
        @endforelse
    </div>

    <div style="margin-top:20px; text-align:center;">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

</body>
</html>
