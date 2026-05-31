<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UMKM Digital Palu - Smart Economy</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
            font-size: 22px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 15px;
        }

        .container {
            padding: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .intro {
            background: #0f766e;
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
        }

        .intro h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .intro p {
            margin: 0;
            color: #ccfbf1;
            line-height: 1.5;
        }

        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .quick-actions a {
            background: rgba(255,255,255,.16);
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin: 0;
            color: #555;
        }

        .card p {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0 0;
            color: #0f766e;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        #map {
            height: 350px;
            border-radius: 12px;
        }

        .status {
            padding: 12px;
            background: #fff7ed;
            border-left: 5px solid #f97316;
            border-radius: 8px;
        }

        .recommendation {
            background: #ecfdf5;
            border-left: 5px solid #10b981;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f1f5f9;
        }

        @media (max-width: 900px) {
            .cards, .grid, .intro {
                grid-template-columns: 1fr;
                flex-direction: column;
            }

            .quick-actions {
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>

@php
    $role = auth()->user()->role;
    $dashboardTitle = [
        'admin' => 'Dashboard Admin',
        'pemerintah' => 'Dashboard Pemerintah',
        'umkm' => 'Dashboard UMKM',
    ][$role] ?? 'Dashboard Smart Economy';

    $dashboardDescription = [
        'admin' => 'Pantau operasional platform, verifikasi UMKM, kategori, produk, dan transaksi.',
        'pemerintah' => 'Analisis perkembangan ekonomi UMKM, persebaran usaha, dan rekomendasi bantuan.',
        'umkm' => 'Kelola performa usaha, produk, transaksi, dan stok produk milik UMKM Anda.',
    ][$role] ?? 'Pantau aktivitas UMKM Digital Palu.';
@endphp

@include('partials.navbar', ['title' => $dashboardTitle])

<div class="container">
    <div class="intro">
        <div>
            <h1>{{ $dashboardTitle }}</h1>
            <p>{{ $dashboardDescription }}</p>
        </div>
        <div class="quick-actions">
            @if($role === 'admin')
                <a href="/umkm">Verifikasi UMKM</a>
                <a href="/admin/kategori">Kelola Kategori</a>
                <a href="/admin/transaksi">Laporan Transaksi</a>
            @elseif($role === 'pemerintah')
                <a href="/umkm">Monitoring UMKM</a>
                <a href="/admin/transaksi">Data Transaksi</a>
            @elseif($role === 'umkm')
                <a href="/umkm/produk/create">Tambah Produk</a>
                <a href="/umkm/produk">Kelola Produk</a>
                <a href="/umkm/transaksi">Riwayat Transaksi</a>
            @endif
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <h3>{{ $role === 'umkm' ? 'UMKM Saya' : 'Jumlah UMKM' }}</h3>
            <p>{{ $jumlahUmkm }}</p>
        </div>

        <div class="card">
            <h3>{{ $role === 'umkm' ? 'Transaksi Masuk' : 'Total Transaksi' }}</h3>
            <p>{{ $totalTransaksi }}</p>
        </div>

        <div class="card">
            <h3>{{ $role === 'umkm' ? 'Omzet Saya' : 'Total Omzet' }}</h3>
            <p>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        </div>

        <div class="card">
            <h3>{{ $role === 'admin' ? 'UMKM Pending' : ($role === 'pemerintah' ? 'Kategori Produk' : 'Produk Saya') }}</h3>
            <p>{{ $role === 'admin' ? $umkmPending : ($role === 'pemerintah' ? $totalKategori : $totalProduk) }}</p>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Grafik Omzet Bulanan</h3>
            <canvas id="omzetChart"></canvas>
        </div>

        <div class="card">
            <h3>Grafik Transaksi Bulanan</h3>
            <canvas id="transaksiChart"></canvas>
        </div>
    </div>

    @if($role !== 'umkm')
        <div class="grid">
            <div class="card">
                <h3>Kategori Produk Terlaris</h3>
                <canvas id="kategoriChart"></canvas>
            </div>

            <div class="card">
                <h3>Pertumbuhan UMKM</h3>
                <canvas id="umkmChart"></canvas>
            </div>
        </div>
    @else
        <div class="card" style="margin-bottom:25px;">
            <h3>Kategori Produk Saya yang Terjual</h3>
            <canvas id="kategoriChart"></canvas>
        </div>
    @endif

    <div class="grid">
        <div class="card">
            <h3>IoT Smart Inventory</h3>

            @if($stokIot)
                <div class="status">
                    <strong>Produk:</strong> {{ $stokIot->nama_produk }} <br>
                    <strong>Stok:</strong> {{ $stokIot->persentase_stok }}% <br>
                    <strong>Status:</strong> {{ $stokIot->status_stok }} <br>
                    <strong>Update:</strong> {{ $stokIot->waktu_update }}
                </div>

                @if($stokIot->persentase_stok < 40)
                    <div class="recommendation">
                        Rekomendasi: Stok mulai menipis. UMKM disarankan segera melakukan restock.
                    </div>
                @endif
            @else
                <p>Belum ada data sensor IoT.</p>
            @endif
        </div>

        @if($role !== 'umkm')
            <div class="card">
                <h3>Rekomendasi Bantuan UMKM Berbasis Data</h3>
                <table>
                    <thead>
                        <tr>
                            <th>UMKM</th>
                            <th>Produk</th>
                            <th>Omzet</th>
                            <th>Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekomendasiUmkm as $item)
                            <tr>
                                <td>{{ $item->nama_umkm }}</td>
                                <td>{{ $item->total_produk }}</td>
                                <td>Rp {{ number_format($item->omzet, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->status_verifikasi !== 'verified')
                                        Perlu verifikasi dan pendampingan awal.
                                    @elseif($item->omzet < 1000000)
                                        Prioritas bantuan modal atau pelatihan digital marketing.
                                    @else
                                        Potensial mengikuti pameran dan perluasan pasar.
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Belum ada data rekomendasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="card">
                <h3>Ringkasan Aksi UMKM</h3>
                <div class="recommendation">
                    Pantau stok manual dan stok IoT secara berkala. Jika status produk menipis, segera perbarui stok atau lakukan restock.
                </div>
                <div class="recommendation">
                    Tambahkan produk dengan deskripsi jelas agar lebih mudah ditemukan pembeli di marketplace.
                </div>
            </div>
        @endif
    </div>

    <div class="card">
        <h3>Peta Persebaran UMKM Kota Palu</h3>
        <div id="map"></div>
    </div>

</div>

<script>
    const omzetLabels = @json($grafikOmzet->pluck('bulan'));
    const omzetData = @json($grafikOmzet->pluck('total'));
    const transaksiLabels = @json($grafikTransaksi->pluck('bulan'));
    const transaksiData = @json($grafikTransaksi->pluck('total'));
    const kategoriLabels = @json($kategoriTerlaris->pluck('nama_kategori'));
    const kategoriData = @json($kategoriTerlaris->pluck('total'));
    const umkmLabels = @json($pertumbuhanUmkm->pluck('bulan'));
    const umkmData = @json($pertumbuhanUmkm->pluck('total'));

    const omzetChart = document.getElementById('omzetChart');
    const transaksiChart = document.getElementById('transaksiChart');
    const kategoriChart = document.getElementById('kategoriChart');
    const umkmChart = document.getElementById('umkmChart');

    if (omzetChart) new Chart(omzetChart, {
        type: 'bar',
        data: {
            labels: omzetLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'Omzet',
                data: omzetData
            }]
        }
    });

    if (transaksiChart) new Chart(transaksiChart, {
        type: 'line',
        data: {
            labels: transaksiLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'Transaksi',
                data: transaksiData
            }]
        }
    });

    if (kategoriChart) new Chart(kategoriChart, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                label: 'Produk Terjual',
                data: kategoriData
            }]
        }
    });

    if (umkmChart) new Chart(umkmChart, {
        type: 'bar',
        data: {
            labels: umkmLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'UMKM Baru',
                data: umkmData
            }]
        }
    });

    const map = L.map('map').setView([-0.9000, 119.8700], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const umkms = @json($umkms);

    umkms.forEach(function(umkm) {
        if (umkm.latitude && umkm.longitude) {
            L.marker([umkm.latitude, umkm.longitude])
                .addTo(map)
                .bindPopup(
                    '<b>' + umkm.nama_umkm + '</b><br>' +
                    umkm.alamat + '<br>' +
                    'Kategori: ' + umkm.kategori_usaha
                );
        }
    });
</script>

</body>
</html>
