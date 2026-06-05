<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
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

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-top: 4px solid #0f766e;
            transition: all .2s ease;
            display: flex;
            flex-direction: column;
        }

        .category-card:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }

        .category-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .category-name {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px;
        }

        .category-count {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .category-count strong {
            color: #0f766e;
            font-size: 18px;
        }

        .category-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn {
            flex: 1;
            padding: 9px 12px;
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

            .categories-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Kelola Kategori'])

<div class="container">
    <div class="page-header">
        <h1>📁 Kelola Kategori</h1>
        <a class="btn-add" href="/admin/kategori/create">+ Tambah Kategori</a>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @forelse($categories as $category)
        <div class="categories-grid" style="display: grid;">
            <div class="category-card">
                <div class="category-icon">
                    {{ match($category->nama_kategori) {
                        'Kuliner' => '🍜',
                        'Kerajinan' => '🎨',
                        'Fashion' => '👔',
                        'Jasa' => '🔧',
                        default => '📦'
                    } }}
                </div>
                <h3 class="category-name">{{ $category->nama_kategori }}</h3>
                <div class="category-count">
                    <strong>{{ $category->total_produk }}</strong> produk
                </div>
                <div class="category-actions">
                    <a class="btn btn-edit" href="/admin/kategori/{{ $category->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/kategori/{{ $category->id }}/delete" style="flex: 1; margin: 0;">
                        @csrf
                        <button class="btn btn-delete" style="width: 100%; margin: 0;" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <h2>Belum ada kategori</h2>
            <p>Mulai dengan membuat kategori produk baru.</p>
            <a class="btn-add" href="/admin/kategori/create">+ Buat Kategori Pertama</a>
        </div>
    @endforelse
</div>

<script>
    document.querySelectorAll('.categories-grid').forEach((grid, i) => {
        if (i === 0) return; // Keep the first grid as is
        const card = grid.querySelector('.category-card');
        if (card && i > 0) {
            const parentGrid = document.querySelector('.categories-grid:first-of-type');
            parentGrid.appendChild(card);
            grid.remove();
        }
    });

    // Better approach: wrap all cards in a single grid
    const allCards = Array.from(document.querySelectorAll('.category-card'));
    if (allCards.length > 0) {
        const firstGrid = document.querySelector('.categories-grid');
        firstGrid.innerHTML = '';
        allCards.forEach(card => firstGrid.appendChild(card));
    }
</script>

</body>
</html>
