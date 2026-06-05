<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Digital Palu - Smart Economy</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #eef2f7;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 36px;
        }

        .intro {
            background: linear-gradient(135deg, #0f766e 0%, #155e75 62%, #1e3a8a 100%);
            color: white;
            padding: 26px;
            border-radius: 8px;
            margin-bottom: 22px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: start;
            box-shadow: 0 18px 35px rgba(15, 23, 42, .16);
        }

        .intro h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
        }

        .intro p {
            max-width: 720px;
            margin: 0;
            color: #dbeafe;
            line-height: 1.55;
        }

        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .quick-actions a,
        .button-link {
            background: rgba(255,255,255,.16);
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            white-space: nowrap;
        }

        .quick-actions a:hover,
        .button-link:hover {
            background: rgba(255,255,255,.25);
        }

        .quick-actions a.btn-kebijakan {
            background: #f59e0b;
            color: #111827;
        }

        .intro.umkm-intro {
            background: linear-gradient(135deg, #0f766e 0%, #2563eb 58%, #7c3aed 100%);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 12px;
            margin: 28px 0 12px;
        }

        .section-title h2 {
            margin: 0;
            font-size: 20px;
        }

        .section-title span {
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(215px, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .grid-wide {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
        }

        .card h3 {
            margin: 0 0 14px;
            color: #334155;
            font-size: 16px;
        }

        .stat-card {
            position: relative;
            min-height: 132px;
            overflow: hidden;
            border-top: 4px solid #0f766e;
        }

        .stat-card.accent-blue {
            border-top-color: #2563eb;
        }

        .stat-card.accent-amber {
            border-top-color: #f59e0b;
        }

        .stat-card.accent-rose {
            border-top-color: #e11d48;
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .stat-value {
            margin-top: 9px;
            color: #0f766e;
            font-size: 30px;
            line-height: 1.15;
            font-weight: 800;
        }

        .stat-note {
            margin-top: 9px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 5px 9px;
            color: #065f46;
            background: #d1fae5;
            font-size: 12px;
            font-weight: 800;
        }

        .badge.warning {
            color: #92400e;
            background: #fef3c7;
        }

        .badge.info {
            color: #1e40af;
            background: #dbeafe;
        }

        .umkm-workspace {
            display: grid;
            grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .action-panel {
            display: grid;
            gap: 12px;
        }

        .action-item {
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            color: #065f46;
            background: #d1fae5;
            font-weight: 900;
        }

        .action-item strong {
            display: block;
            margin-bottom: 3px;
            color: #111827;
        }

        .action-item span {
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .action-item a {
            color: #0f766e;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }

        .mini-table {
            display: grid;
            gap: 10px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
            gap: 12px;
        }

        .summary-stat {
            min-height: 126px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #0f766e;
            border-radius: 8px;
            background: #f8fafc;
        }

        .summary-stat.accent-amber {
            border-top-color: #f59e0b;
        }

        .mini-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .mini-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .mini-row strong {
            color: #111827;
        }

        .mini-row small {
            display: block;
            margin-top: 4px;
            color: #64748b;
        }

        .empty-state {
            padding: 16px;
            border-radius: 8px;
            color: #64748b;
            background: #f8fafc;
            line-height: 1.5;
        }

        .policy-list {
            display: grid;
            gap: 12px;
        }

        .policy-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            display: grid;
            gap: 8px;
            background: #f8fafc;
        }

        .policy-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
        }

        .policy-head strong {
            color: #111827;
        }

        .policy-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            color: #475569;
            font-size: 13px;
            line-height: 1.45;
        }

        .recommendation {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 12px;
            border-radius: 8px;
            color: #065f46;
            line-height: 1.5;
            margin-top: 10px;
        }

        .recommendation.warning {
            background: #fff7ed;
            border-left-color: #f97316;
            color: #92400e;
        }

        .chart-box {
            position: relative;
            min-height: 305px;
        }

        .chart-box canvas {
            width: 100% !important;
            height: 305px !important;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            min-width: 620px;
        }

        th, td {
            padding: 11px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        #map {
            height: 370px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 900px) {
            .intro,
            .grid,
            .grid-wide,
            .umkm-workspace {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                justify-content: flex-start;
            }
        }

        @media (max-width: 560px) {
            .container {
                width: min(100% - 20px, 1180px);
                padding-top: 16px;
            }

            .intro {
                padding: 20px;
            }

            .intro h1 {
                font-size: 24px;
            }

            .policy-head,
            .section-title {
                align-items: flex-start;
                flex-direction: column;
            }

            .policy-meta {
                grid-template-columns: 1fr;
            }

            .summary-stats {
                grid-template-columns: 1fr;
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
        'pemerintah' => 'Pantau indikator ekonomi daerah, performa sektor usaha, dan kebijakan intervensi UMKM Kota Palu.',
        'umkm' => 'Kelola performa usaha, produk, transaksi, dan stok produk milik UMKM Anda.',
    ][$role] ?? 'Pantau aktivitas UMKM Digital Palu.';

    $formatPercent = fn ($value) => number_format($value, 1, ',', '.') . '%';
@endphp

@include('partials.navbar', ['title' => $dashboardTitle])

<main class="container">
    <section class="intro {{ $role === 'umkm' ? 'umkm-intro' : '' }}">
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
                <a href="#kebijakan" class="btn-kebijakan">Kebijakan</a>
                <a href="#indikator">Indikator Ekonomi</a>
                <a href="#sektoral">Grafik Sektoral</a>
                <a href="/admin/transaksi">Data Transaksi</a>
            @elseif($role === 'umkm')
                <a href="/umkm/produk/create">Tambah Produk</a>
                <a href="/umkm/produk">Kelola Produk</a>
                <a href="/umkm/transaksi">Riwayat Transaksi</a>
            @endif
        </div>
    </section>

    @if($role === 'pemerintah')
        <div class="section-title" id="indikator">
            <h2>Indikator Ekonomi</h2>
            <span>Ringkasan kinerja UMKM daerah</span>
        </div>
        <section class="cards">
            <div class="card stat-card">
                <div class="stat-label">UMKM Terdata</div>
                <div class="stat-value">{{ $jumlahUmkm }}</div>
                <div class="stat-note">{{ $indikatorEkonomi['umkm_terverifikasi'] }} terverifikasi, {{ $formatPercent($indikatorEkonomi['rasio_verifikasi']) }} rasio verifikasi</div>
            </div>
            <div class="card stat-card accent-blue">
                <div class="stat-label">Omzet Bulan Ini</div>
                <div class="stat-value">Rp {{ number_format($indikatorEkonomi['omzet_bulan_ini'], 0, ',', '.') }}</div>
                <div class="stat-note">{{ $formatPercent($indikatorEkonomi['pertumbuhan_omzet']) }} dibanding bulan sebelumnya</div>
            </div>
            <div class="card stat-card accent-amber">
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value">Rp {{ number_format($indikatorEkonomi['rata_rata_transaksi'], 0, ',', '.') }}</div>
                <div class="stat-note">{{ $totalTransaksi }} transaksi tercatat</div>
            </div>
            <div class="card stat-card accent-rose">
                <div class="stat-label">Sektor Aktif</div>
                <div class="stat-value">{{ $indikatorEkonomi['sektor_aktif'] }}</div>
                <div class="stat-note">{{ $totalProduk }} produk dari sektor UMKM terpantau</div>
            </div>
        </section>

        <section class="grid-wide" id="kebijakan">
            <div class="card">
                <h3>Kebijakan Prioritas</h3>
                <div class="policy-list">
                    @foreach($kebijakanPrioritas as $kebijakan)
                        <div class="policy-item">
                            <div class="policy-head">
                                <strong>{{ $kebijakan['judul'] }}</strong>
                                <span class="badge {{ str_contains($kebijakan['status'], 'Tinggi') || str_contains($kebijakan['status'], 'Tindak') ? 'warning' : 'info' }}">
                                    {{ $kebijakan['status'] }}
                                </span>
                            </div>
                            <div class="policy-meta">
                                <span><strong>Sasaran:</strong> {{ $kebijakan['sasaran'] }}</span>
                                <span><strong>Indikator:</strong> {{ $kebijakan['indikator'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <h3>Arah Intervensi</h3>
                <div class="recommendation warning">
                    Prioritaskan pendampingan pada UMKM beromzet rendah, belum terverifikasi, dan sektor yang belum memiliki variasi produk cukup.
                </div>
                <div class="recommendation">
                    Sektor dengan omzet tertinggi dapat diarahkan ke program pameran daerah, kemitraan retail, dan kurasi produk unggulan.
                </div>
            </div>
        </section>
    @else
        <section class="cards">
            <div class="card stat-card">
                <div class="stat-label">{{ $role === 'umkm' ? 'UMKM Saya' : 'Jumlah UMKM' }}</div>
                <div class="stat-value">{{ $jumlahUmkm }}</div>
                <div class="stat-note">{{ $role === 'admin' ? 'Data pelaku usaha terdaftar' : 'Profil usaha aktif dan siap dipantau' }}</div>
            </div>
            <div class="card stat-card accent-blue">
                <div class="stat-label">{{ $role === 'umkm' ? 'Pesanan Masuk' : 'Total Transaksi' }}</div>
                <div class="stat-value">{{ $totalTransaksi }}</div>
                <div class="stat-note">{{ $role === 'umkm' ? 'Order dari checkout pembeli' : 'Transaksi tercatat pada platform' }}</div>
            </div>
            <div class="card stat-card accent-amber">
                <div class="stat-label">{{ $role === 'umkm' ? 'Omzet Saya' : 'Total Omzet' }}</div>
                <div class="stat-value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
                <div class="stat-note">Akumulasi nilai penjualan</div>
            </div>
            <div class="card stat-card accent-rose">
                <div class="stat-label">{{ $role === 'admin' ? 'UMKM Pending' : 'Produk Saya' }}</div>
                <div class="stat-value">{{ $role === 'admin' ? $umkmPending : $totalProduk }}</div>
                <div class="stat-note">{{ $role === 'admin' ? 'Menunggu verifikasi' : $stokMenipis . ' produk perlu cek stok' }}</div>
            </div>
        </section>
    @endif

    @if($role === 'umkm')
        <section class="umkm-workspace">
            <div class="card">
                <h3>Prioritas Hari Ini</h3>
                <div class="action-panel">
                    <div class="action-item">
                        <div class="action-icon">1</div>
                        <div>
                            <strong>Tambah produk baru</strong>
                            <span>Lengkapi foto, kategori, harga, stok, dan deskripsi produk.</span>
                        </div>
                        <a href="/umkm/produk/create">Tambah</a>
                    </div>
                    <div class="action-item">
                        <div class="action-icon">2</div>
                        <div>
                            <strong>Cek stok menipis</strong>
                            <span>{{ $stokMenipis }} produk stoknya 10 atau kurang, {{ $stokKosong }} produk kosong.</span>
                        </div>
                        <a href="/umkm/produk">Cek</a>
                    </div>
                    <div class="action-item">
                        <div class="action-icon">3</div>
                        <div>
                            <strong>Pantau pesanan</strong>
                            <span>{{ $pesananPerluDiproses }} pesanan checkout perlu diproses penjual.</span>
                        </div>
                        <a href="/umkm/transaksi">Lihat</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Ringkasan Toko</h3>
                <div class="summary-stats">
                    <div class="summary-stat">
                        <div class="stat-label">Nilai Stok</div>
                        <div class="stat-value">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</div>
                        <div class="stat-note">Estimasi harga x stok manual</div>
                    </div>
                    <div class="summary-stat accent-amber">
                        <div class="stat-label">Stok Kosong</div>
                        <div class="stat-value">{{ $stokKosong }}</div>
                        <div class="stat-note">Perlu restock atau arsip produk</div>
                    </div>
                    <div class="summary-stat">
                        <div class="stat-label">Perlu Diproses</div>
                        <div class="stat-value">{{ $pesananPerluDiproses }}</div>
                        <div class="stat-note">Pesanan berstatus paid</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid">
            <div class="card">
                <h3>Produk Terlaris</h3>
                <div class="mini-table">
                    @forelse($produkTerlarisUmkm as $produk)
                        <div class="mini-row">
                            <div>
                                <strong>{{ $produk->nama_produk }}</strong>
                                <small>{{ $produk->total_terjual }} terjual, stok {{ $produk->stok_manual }}</small>
                            </div>
                            <span class="badge">Rp {{ number_format($produk->omzet, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada produk terjual. Tambahkan produk yang lengkap lalu pantau performanya di sini.</div>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <h3>Pesanan Masuk Terbaru</h3>
                <div class="mini-table">
                    @forelse($transaksiTerbaruUmkm as $transaksi)
                        <div class="mini-row">
                            <div>
                                <strong>#{{ $transaksi->order_id }} - {{ $transaksi->nama_produk }}</strong>
                                <small>{{ $transaksi->pembeli }} checkout {{ $transaksi->jumlah }} item pada {{ \Carbon\Carbon::parse($transaksi->tanggal_order)->format('d M Y H:i') }}</small>
                            </div>
                            <span class="badge {{ $transaksi->status_order === 'pending' ? 'warning' : 'info' }}">{{ ucfirst($transaksi->status_order) }}</span>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada pesanan checkout yang masuk ke UMKM Anda.</div>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    @if($role === 'admin' && $umkmPending > 0)
        <section class="card recommendation warning" style="margin-bottom: 22px;">
            <h3 style="margin-top: 0; color: #92400e;">UMKM Menunggu Verifikasi</h3>
            <p>Ada {{ $umkmPending }} UMKM yang perlu diverifikasi agar dapat mulai berjualan di marketplace.</p>
            <a class="button-link" style="background: #f97316; color: white;" href="/umkm">Lihat UMKM Pending</a>
        </section>
    @endif

    <section class="grid">
        <div class="card">
            <h3>Grafik Omzet Bulanan</h3>
            <div class="chart-box"><canvas id="omzetChart"></canvas></div>
        </div>
        <div class="card">
            <h3>Grafik Transaksi Bulanan</h3>
            <div class="chart-box"><canvas id="transaksiChart"></canvas></div>
        </div>
    </section>

    <div class="section-title" id="sektoral">
        <h2>{{ $role === 'pemerintah' ? 'Grafik Sektoral' : 'Analitik Produk' }}</h2>
        <span>{{ $role === 'pemerintah' ? 'Komposisi omzet dan aktivitas sektor' : 'Ringkasan kategori terjual' }}</span>
    </div>

    <section class="grid">
        <div class="card">
            <h3>{{ $role === 'umkm' ? 'Kategori Produk Saya yang Terjual' : 'Kategori Produk Terlaris' }}</h3>
            <div class="chart-box"><canvas id="kategoriChart"></canvas></div>
        </div>

        @if($role === 'pemerintah')
            <div class="card">
                <h3>Omzet per Sektor Usaha</h3>
                <div class="chart-box"><canvas id="sektorChart"></canvas></div>
            </div>
        @elseif($role !== 'umkm')
            <div class="card">
                <h3>Pertumbuhan UMKM</h3>
                <div class="chart-box"><canvas id="umkmChart"></canvas></div>
            </div>
        @else
            <div class="card">
                <h3>Ringkasan Operasional</h3>
                <div class="recommendation">Pantau stok produk manual secara berkala agar data katalog dan transaksi tetap akurat.</div>
            </div>
        @endif
    </section>

    @if($role === 'pemerintah')
        <section class="card" style="margin-bottom: 22px;">
            <h3>Tabel Performa Sektoral</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Sektor</th>
                            <th>UMKM</th>
                            <th>Produk</th>
                            <th>Transaksi</th>
                            <th>Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performaSektoral as $sektor)
                            <tr>
                                <td>{{ $sektor->sektor }}</td>
                                <td>{{ $sektor->total_umkm }}</td>
                                <td>{{ $sektor->total_produk }}</td>
                                <td>{{ $sektor->total_transaksi }}</td>
                                <td>Rp {{ number_format($sektor->omzet, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Belum ada data sektor usaha.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if($role !== 'umkm')
        <section class="card" style="margin-bottom: 22px;">
            <h3>Rekomendasi Bantuan UMKM Berbasis Data Kinerja</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>UMKM</th>
                            <th>Produk</th>
                            <th>Omzet</th>
                            <th>Rekomendasi Kebijakan</th>
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
                                        Akselerasi legalitas dan pendampingan awal.
                                    @elseif($item->omzet < 1000000)
                                        Prioritas stimulus modal atau pelatihan pemasaran digital.
                                    @else
                                        Potensial untuk pameran daerah dan perluasan pasar.
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Belum ada data evaluasi kebijakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <section class="card">
        <h3>Peta Persebaran UMKM Kota Palu</h3>
        <div id="map"></div>
    </section>
</main>

<script>
    const omzetLabels = @json($grafikOmzet->pluck('bulan'));
    const omzetData = @json($grafikOmzet->pluck('total'));
    const transaksiLabels = @json($grafikTransaksi->pluck('bulan'));
    const transaksiData = @json($grafikTransaksi->pluck('total'));
    const kategoriLabels = @json($kategoriTerlaris->pluck('nama_kategori'));
    const kategoriData = @json($kategoriTerlaris->pluck('total'));
    const umkmLabels = @json($pertumbuhanUmkm->pluck('bulan'));
    const umkmData = @json($pertumbuhanUmkm->pluck('total'));
    const sektorLabels = @json(($performaSektoral ?? collect())->pluck('sektor'));
    const sektorOmzet = @json(($performaSektoral ?? collect())->pluck('omzet'));
    const chartColors = ['#0f766e', '#2563eb', '#f59e0b', '#e11d48', '#7c3aed', '#0891b2'];

    const rupiah = value => 'Rp ' + Number(value).toLocaleString('id-ID');
    const monthLabel = bulan => bulan ? 'Bulan ' + bulan : '-';

    if (document.getElementById('omzetChart')) new Chart(document.getElementById('omzetChart'), {
        type: 'bar',
        data: {
            labels: omzetLabels.map(monthLabel),
            datasets: [{
                label: 'Omzet',
                data: omzetData,
                backgroundColor: '#0f766e',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: rupiah } } }
        }
    });

    if (document.getElementById('transaksiChart')) new Chart(document.getElementById('transaksiChart'), {
        type: 'line',
        data: {
            labels: transaksiLabels.map(monthLabel),
            datasets: [{
                label: 'Transaksi',
                data: transaksiData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, .12)',
                pointBackgroundColor: '#2563eb',
                tension: .3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } } }
        }
    });

    if (document.getElementById('kategoriChart')) new Chart(document.getElementById('kategoriChart'), {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                label: 'Produk Terjual',
                data: kategoriData,
                backgroundColor: chartColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: { legend: { position: 'bottom' } }
        }
    });

    if (document.getElementById('sektorChart')) new Chart(document.getElementById('sektorChart'), {
        type: 'bar',
        data: {
            labels: sektorLabels,
            datasets: [{
                label: 'Omzet Sektor',
                data: sektorOmzet,
                backgroundColor: chartColors,
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { callback: rupiah } } }
        }
    });

    if (document.getElementById('umkmChart')) new Chart(document.getElementById('umkmChart'), {
        type: 'bar',
        data: {
            labels: umkmLabels.map(monthLabel),
            datasets: [{
                label: 'UMKM Baru',
                data: umkmData,
                backgroundColor: '#f59e0b',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } } }
        }
    });

    const map = L.map('map').setView([-0.9000, 119.8700], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const umkms = @json($umkms);

    umkms.forEach(function(umkm) {
        if (umkm.latitude && umkm.longitude) {
            L.marker([umkm.latitude, umkm.longitude])
                .addTo(map)
                .bindPopup(
                    '<b>' + umkm.nama_umkm + '</b><br>' +
                    umkm.alamat + '<br>' +
                    'Sektor Usaha: ' + (umkm.kategori_usaha || 'Belum dikategorikan')
                );
        }
    });
</script>

</body>
</html>
