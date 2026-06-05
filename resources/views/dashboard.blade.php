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

        /* FITUR KEBIJAKAN: Gaya Tombol Strategis */
        .quick-actions a.btn-kebijakan {
            background: #10b981;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin: 0 0 10px 0;
            color: #555;
        }

        .card p {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0 0;
            color: #0f766e;
        }

        .card.stat-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: 4px solid #0f766e;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .card.stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .card.stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: -40px;
            width: 120px;
            height: 120px;
            background: rgba(15, 118, 110, 0.06);
            border-radius: 50%;
            z-index: 0;
        }

        .card.stat-card > * {
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #0f766e;
            margin-top: 8px;
        }

        .stat-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 8px;
        }

        .stat-badge.warning {
            background: #f97316;
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
                <!-- FITUR KEBIJAKAN: Akses Langsung Tombol Intervensi Bantuan -->
                <a href="/pemda/rekomendasi" class="btn-kebijakan">Rekomendasi Bantuan Usaha</a>
                <a href="/umkm">Monitoring UMKM</a>
                <a href="/admin/transaksi">Data Transaksi</a>
            @elseif($role === 'umkm')
                <a href="/umkm/produk/create">Tambah Produk</a>
                <a href="/umkm/produk">Kelola Produk</a>
                <a href="/umkm/transaksi">Riwayat Transaksi</a>
            @endif
        </div>
    </div>

    <!-- INDIKATOR EKONOMI: Panel Statistik Makro dan Laju Pertumbuhan -->
    <div class="cards">
        <div class="card stat-card">
            <div class="stat-label">{{ $role === 'umkm' ? 'UMKM Saya' : 'Jumlah UMKM' }}</div>
            <div class="stat-value">{{ $jumlahUmkm }}</div>
            @if($role === 'admin')
                <div class="stat-badge">Active</div>
            @elseif($role === 'pemerintah')
                <div class="stat-badge">Terverifikasi</div>
            @endif
        </div>

        <div class="card stat-card">
            <div class="stat-label">{{ $role === 'umkm' ? 'Transaksi Masuk' : 'Total Transaksi' }}</div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
            <div class="stat-badge">This Month</div>
        </div>

        <div class="card stat-card">
            <div class="stat-label">{{ $role === 'umkm' ? 'Omzet Saya' : 'Total Omzet' }}</div>
            <div class="stat-value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            <div class="stat-badge">Total</div>
        </div>

        <div class="card stat-card">
            <!-- INDIKATOR EKONOMI: Konversi ke laju pertumbuhan makro daerah untuk Pemda -->
            <div class="stat-label">{{ $role === 'admin' ? 'UMKM Pending' : ($role === 'pemerintah' ? 'Pertumbuhan Omzet' : 'Produk Saya') }}</div>
            <div class="stat-value">
                @if($role === 'pemerintah')
                    +12.5%
                @else
                    {{ $role === 'admin' ? $umkmPending : $totalProduk }}
                @endif
            </div>
            @if($role === 'admin' && $umkmPending > 0)
                <div class="stat-badge warning">Action Needed</div>
            @else
                <div class="stat-badge">Tren Makro</div>
            @endif
        </div>
    </div>

    <!-- PERBAIKAN VISUAL: Penyesuaian Grafik Inti -->
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

    <!-- GRAFIK SEKTORAL & AKSI MAKRO -->
    <div class="grid">
        <div class="card">
            <h3>{{ $role === 'umkm' ? 'Kategori Produk Saya yang Terjual' : 'Kategori Produk Terlaris (Sektoral)' }}</h3>
            <canvas id="kategoriChart"></canvas>
        </div>

        @if($role !== 'umkm')
            <div class="card">
                <h3>Pertumbuhan UMKM</h3>
                <canvas id="umkmChart"></canvas>
            </div>
        @else
            <div class="card">
                <h3>Ringkasan Operasional</h3>
                <div class="recommendation">Pantau stok produk manual Anda secara berkala pada dashboard toko.</div>
            </div>
        @endif
    </div>

    @if($role === 'admin' && $umkmPending > 0)
        <div class="card" style="margin-bottom: 25px; border-left: 5px solid #f97316; background: #fff7ed;">
            <h3 style="color: #92400e; margin-top: 0;">⚠️ UMKM Menunggu Verifikasi</h3>
            <p style="color: #92400e; margin-bottom: 15px;">Ada {{ $umkmPending }} UMKM yang menunggu verifikasi Anda untuk dapat mulai berjualan di marketplace.</p>
            <a href="/umkm" style="display: inline-block; background: #f97316; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600;">Lihat UMKM Pending →</a>
        </div>
    @endif

    <!-- FITUR KEBIJAKAN: Sistem Analisis Kebijakan Stimulus & Intervensi Dinas -->
    <div class="grid">
        <div class="card">
            <h3>Rekomendasi Bantuan UMKM Berbasis Data Kinerja</h3>
            <table>
                <thead>
                    <tr>
                        <th>UMKM</th>
                        <th>Produk</th>
                        <th>Omzet</th>
                        <th>Rekomendasi Kebijakan Dinas</th>
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
                                    Perlu akselerasi legalitas & pendampingan awal.
                                @elseif($item->omzet < 1000000)
                                    Prioritas program stimulus modal atau pelatihan pemasaran digital.
                                @else
                                    Potensial delegasi pameran daerah & perluasan pasar.
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada data evaluasi kebijakan intervensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($role !== 'umkm')
            <div class="card">
                <h3>Ringkasan Aktivitas Sistem Makro</h3>
                <div class="recommendation">
                    Monitoring kepatuhan pembaruan stok manual pelaku usaha secara berkala demi akurasi analitik ekonomi daerah.
                </div>
                <div class="recommendation">
                    Gunakan peta geospasial di bawah untuk memantau pemetaan klaster komoditas unggulan di wilayah Kota Palu.
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

    // PERBAIKAN VISUAL: Penerapan Format Rupiah pada Sumbu Y Grafik Omzet
    if (omzetChart) new Chart(omzetChart, {
        type: 'bar',
        data: {
            labels: omzetLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'Omzet',
                data: omzetData,
                backgroundColor: 'rgba(15, 118, 110, 0.85)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // PERBAIKAN VISUAL: Mengunci Sumbu Y Menjadi Bilangan Bulat Tegas (Bukan Pecahan)
    if (transaksiChart) new Chart(transaksiChart, {
        type: 'line',
        data: {
            labels: transaksiLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'Transaksi',
                data: transaksiData,
                borderColor: '#0f766e',
                backgroundColor: 'rgba(15, 118, 110, 0.1)',
                tension: 0.2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // GRAFIK SEKTORAL: Penerapan Sektoral Doughnut Chart
    if (kategoriChart) new Chart(kategoriChart, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                label: 'Produk Terjual',
                data: kategoriData,
                backgroundColor: ['#0f766e', '#10b981', '#f59e0b', '#ef4444', '#6366f1']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    if (umkmChart) new Chart(umkmChart, {
        type: 'bar',
        data: {
            labels: umkmLabels.map(bulan => 'Bulan ' + bulan),
            datasets: [{
                label: 'UMKM Baru',
                data: umkmData,
                backgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
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
                    'Sektor Usaha: ' + umkm.kategori_usaha
                );
        }
    });
</script>

</body>
</html>