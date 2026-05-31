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
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Marketplace Produk'])

<div class="container">
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
                    {{ strtoupper(substr($product->nama_produk, 0, 1)) }}
                </div>

                <div class="content">
                    <span class="badge">{{ $product->nama_kategori }}</span>

                    <h3>{{ $product->nama_produk }}</h3>

                    <div class="price">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </div>

                    <div class="info">
                        <strong>UMKM:</strong> {{ $product->nama_umkm }} <br>
                        <strong>Deskripsi:</strong> {{ $product->deskripsi }}
                    </div>

                    <div class="stock">
                        <strong>Stok Manual:</strong> {{ $product->stok_manual }} <br>
                        <strong>Stok IoT:</strong> {{ $product->stok_iot }}% <br>
                        <strong>Status:</strong> {{ $product->status_stok }}
                    </div>

                    <form method="POST" action="/keranjang/{{ $product->id }}/add">
                        @csrf
                        <button class="btn" type="submit">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        @empty
            <p>Produk tidak ditemukan.</p>
        @endforelse
    </div>
</div>

</body>
</html>
