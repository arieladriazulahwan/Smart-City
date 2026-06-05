<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring UMKM</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .page-header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 28px;
        }

        .btn-add {
            padding: 11px 20px;
            border-radius: 8px;
            text-decoration: none;
            border: 0;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            transition: transform .2s ease, box-shadow .2s ease;
            font-size: 14px;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 118, 110, 0.3);
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

        .umkm-grid {
            display: grid;
            gap: 20px;
        }

        .umkm-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #0f766e;
            transition: all .2s ease;
        }

        .umkm-card:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }

        .umkm-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .umkm-info h3 {
            margin: 0 0 6px;
            color: #1f2937;
            font-size: 18px;
            font-weight: 700;
        }

        .umkm-contact {
            color: #6b7280;
            font-size: 13px;
            margin: 0 0 8px;
        }

        .badge {
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

        .status-verified {
            background: #dcfce7;
            color: #166534;
        }

        .umkm-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .meta-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
        }

        .umkm-address {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 12px;
        }

        .umkm-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            border: 0;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
            transition: all .2s ease;
            text-align: center;
        }

        .btn-edit {
            background: #fbbf24;
            color: #78350f;
        }

        .btn-edit:hover {
            background: #f59e0b;
        }

        .btn-verify {
            background: #93c5fd;
            color: #1e40af;
        }

        .btn-verify:hover {
            background: #60a5fa;
        }

        .btn-delete {
            background: #fecaca;
            color: #7f1d1d;
        }

        .btn-delete:hover {
            background: #fca5a5;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .umkm-header {
                flex-direction: column;
            }

            .umkm-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .umkm-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Monitoring UMKM'])

<div class="container">
    <div class="page-header">
        <h1>🏪 Monitoring UMKM</h1>
        @if(in_array(auth()->user()->role, ['admin', 'umkm']))
            <a class="btn-add" href="/umkm/create">+ Tambah UMKM</a>
        @endif
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="umkm-grid">
        @forelse($umkms as $index => $umkm)
            <div class="umkm-card">
                <div class="umkm-header">
                    <div class="umkm-info">
                        <h3>{{ $umkm->nama_umkm }}</h3>
                        <div class="umkm-contact">{{ $umkm->name }} • {{ $umkm->email }}</div>
                    </div>
                    <span class="badge status-{{ $umkm->status_verifikasi }}">
                        {{ ucfirst($umkm->status_verifikasi) }}
                    </span>
                </div>

                <div class="umkm-meta">
                    <div class="meta-item">
                        <div class="meta-label">Kategori</div>
                        <div class="meta-value">{{ $umkm->kategori_usaha ?? '-' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Total Produk</div>
                        <div class="meta-value">{{ $umkm->total_produk }}</div>
                    </div>
                </div>

                <div class="umkm-address">
                    <strong>📍 Lokasi:</strong> {{ $umkm->alamat }}<br>
                    <small>{{ $umkm->latitude }}, {{ $umkm->longitude }}</small>
                </div>

                <div class="umkm-actions">
                    <a class="btn btn-edit" href="/umkm/{{ $umkm->id }}/edit">Edit</a>
                    @if(auth()->user()->role === 'admin' && $umkm->status_verifikasi !== 'verified')
                        <form method="POST" action="/umkm/{{ $umkm->id }}/verify" style="margin: 0;">
                            @csrf
                            <button class="btn btn-verify">Verifikasi</button>
                        </form>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <form method="POST" action="/umkm/{{ $umkm->id }}/delete" style="margin: 0;">
                            @csrf
                            <button class="btn btn-delete" onclick="return confirm('Hapus UMKM ini?')">Hapus</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <h2>Belum ada data UMKM</h2>
                <p>Mulai dengan menambahkan UMKM baru ke sistem.</p>
                @if(in_array(auth()->user()->role, ['admin', 'umkm']))
                    <a class="btn-add" href="/umkm/create">+ Buat UMKM Pertama</a>
                @endif
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
