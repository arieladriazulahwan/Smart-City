<style>
    .app-navbar {
        background: #0f766e;
        color: white;
        padding: 16px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
    }

    .app-navbar__brand {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .app-navbar__brand strong {
        font-size: 20px;
        line-height: 1.2;
    }

    .app-navbar__brand span {
        color: #ccfbf1;
        font-size: 13px;
        font-weight: normal;
    }

    .app-navbar__role {
        align-self: flex-start;
        background: rgba(255,255,255,.16);
        color: white;
        border-radius: 999px;
        padding: 4px 9px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 4px;
        width: fit-content;
    }

    .app-navbar__links {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .app-navbar a,
    .app-navbar button {
        color: white;
        text-decoration: none;
        border: 0;
        background: transparent;
        border-radius: 7px;
        padding: 8px 10px;
        font: inherit;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
    }

    .app-navbar a:hover,
    .app-navbar button:hover,
    .app-navbar .active {
        background: rgba(255,255,255,.14);
    }

    .app-navbar form {
        margin: 0;
        display: inline;
    }

    @media (max-width: 760px) {
        .app-navbar {
            align-items: flex-start;
            flex-direction: column;
            padding: 15px 18px;
        }

        .app-navbar__links {
            justify-content: flex-start;
        }
    }
</style>

@php
    $role = auth()->check() ? auth()->user()->role : null;
    $active = fn (...$patterns) => request()->is(...$patterns) ? 'active' : '';

    $roleLabels = [
        'admin' => 'Admin',
        'pemerintah' => 'Pemerintah',
        'umkm' => 'UMKM',
        'pembeli' => 'Pembeli',
    ];

    $menus = [
        'admin' => [
            ['label' => 'Dashboard', 'url' => '/dashboard', 'patterns' => ['dashboard', 'admin/dashboard']],
            ['label' => 'Kelola UMKM', 'url' => '/umkm', 'patterns' => ['umkm', 'umkm/create', 'umkm/*/edit']],
            ['label' => 'Kelola Kategori', 'url' => '/admin/kategori', 'patterns' => ['admin/kategori*']],
            ['label' => 'Laporan Transaksi', 'url' => '/admin/transaksi', 'patterns' => ['admin/transaksi*']],
            ['label' => 'Marketplace', 'url' => '/produk', 'patterns' => ['produk']],
        ],
        'pemerintah' => [
            ['label' => 'Dashboard', 'url' => '/dashboard', 'patterns' => ['dashboard', 'pemerintah/dashboard']],
            ['label' => 'Monitoring UMKM', 'url' => '/umkm', 'patterns' => ['umkm']],
            ['label' => 'Laporan Transaksi', 'url' => '/admin/transaksi', 'patterns' => ['admin/transaksi*']],
            ['label' => 'Marketplace', 'url' => '/produk', 'patterns' => ['produk']],
        ],
        'umkm' => [
            ['label' => 'Dashboard', 'url' => '/dashboard', 'patterns' => ['dashboard', 'umkm/dashboard']],
            ['label' => 'Profil UMKM', 'url' => '/umkm', 'patterns' => ['umkm', 'umkm/create', 'umkm/*/edit']],
            ['label' => 'Kelola Produk', 'url' => '/umkm/produk', 'patterns' => ['umkm/produk*']],
            ['label' => 'Riwayat Transaksi', 'url' => '/umkm/transaksi', 'patterns' => ['umkm/transaksi']],
            ['label' => 'Marketplace', 'url' => '/produk', 'patterns' => ['produk']],
        ],
        'pembeli' => [
            ['label' => 'Marketplace', 'url' => '/produk', 'patterns' => ['produk']],
            ['label' => 'Keranjang', 'url' => '/keranjang', 'patterns' => ['keranjang']],
            ['label' => 'Pesanan Saya', 'url' => '/pesanan', 'patterns' => ['pesanan']],
        ],
    ];

    $currentMenus = $menus[$role] ?? [
        ['label' => 'Marketplace', 'url' => '/produk', 'patterns' => ['produk']],
    ];
@endphp

<div class="app-navbar">
    <div class="app-navbar__brand">
        <strong>{{ $title ?? 'UMKM Digital Palu' }}</strong>
        <span>Smart Economy Kota Palu</span>
        @if($role)
            <div class="app-navbar__role">{{ $roleLabels[$role] ?? ucfirst($role) }}</div>
        @endif
    </div>

    <div class="app-navbar__links">
        @php $cartCount = array_sum(session('cart', [])); @endphp
        @foreach($currentMenus as $menu)
            @php $label = $menu['label']; @endphp
            @if($menu['url'] === '/keranjang' && $cartCount > 0)
                @php $label .= " (" . $cartCount . ")"; @endphp
            @endif
            <a class="{{ $active(...$menu['patterns']) }}" href="{{ $menu['url'] }}">{{ $label }}</a>
        @endforeach

        @auth
            <a href="/profile">Profil</a>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </div>
</div>
