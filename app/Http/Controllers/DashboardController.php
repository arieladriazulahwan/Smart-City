<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function monthExpression(string $column): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "strftime('%m', {$column})"
            : "MONTH({$column})";
    }

    public function redirectByRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($role === 'pemerintah') {
            return redirect('/pemerintah/dashboard');
        }

        if ($role === 'umkm') {
            return redirect('/umkm/dashboard');
        }

        return redirect('/produk');
    }

    public function index()
    {
        $user = Auth::user();
        abort_if(request()->is('admin/*') && $user->role !== 'admin', 403);
        abort_if(request()->is('pemerintah/*') && $user->role !== 'pemerintah', 403);
        abort_if(request()->is('umkm/*') && $user->role !== 'umkm', 403);

        $umkmIds = null;

        if ($user->role === 'umkm') {
            $umkmIds = DB::table('umkms')->where('user_id', $user->id)->pluck('id');
        }

        $jumlahUmkm = DB::table('umkms')
            ->when($user->role === 'umkm', fn ($query) => $query->where('user_id', $user->id))
            ->count();

        $totalProduk = DB::table('products')
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('umkm_id', $umkmIds))
            ->count();

        $umkmPending = DB::table('umkms')->where('status_verifikasi', 'pending')->count();
        $totalKategori = DB::table('categories')->count();

        $orderQuery = DB::table('orders');
        if ($umkmIds !== null) {
            $orderQuery->whereIn('orders.id', function ($query) use ($umkmIds) {
                $query->select('order_details.order_id')
                    ->from('order_details')
                    ->join('products', 'order_details.product_id', '=', 'products.id')
                    ->whereIn('products.umkm_id', $umkmIds);
            });
        }

        $totalTransaksi = (clone $orderQuery)->count();
        $totalOmzet = (clone $orderQuery)->sum('total_harga');

        $umkms = DB::table('umkms')
            ->when($user->role === 'umkm', fn ($query) => $query->where('user_id', $user->id))
            ->get();

        $grafikOmzet = DB::table('orders')
            ->selectRaw($this->monthExpression('tanggal_order') . ' as bulan, SUM(total_harga) as total')
            ->when($umkmIds !== null, function ($query) use ($umkmIds) {
                $query->whereIn('orders.id', function ($subquery) use ($umkmIds) {
                    $subquery->select('order_details.order_id')
                        ->from('order_details')
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->whereIn('products.umkm_id', $umkmIds);
                });
            })
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $grafikTransaksi = DB::table('orders')
            ->selectRaw($this->monthExpression('tanggal_order') . ' as bulan, COUNT(*) as total')
            ->when($umkmIds !== null, function ($query) use ($umkmIds) {
                $query->whereIn('orders.id', function ($subquery) use ($umkmIds) {
                    $subquery->select('order_details.order_id')
                        ->from('order_details')
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->whereIn('products.umkm_id', $umkmIds);
                });
            })
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $kategoriTerlaris = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.nama_kategori', DB::raw('SUM(order_details.jumlah) as total'))
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('products.umkm_id', $umkmIds))
            ->groupBy('categories.nama_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pertumbuhanUmkm = DB::table('umkms')
            ->selectRaw($this->monthExpression('created_at') . ' as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $rekomendasiUmkm = DB::table('umkms')
            ->leftJoin('products', 'umkms.id', '=', 'products.umkm_id')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->select(
                'umkms.nama_umkm',
                'umkms.status_verifikasi',
                DB::raw('COUNT(DISTINCT products.id) as total_produk'),
                DB::raw('COALESCE(SUM(order_details.subtotal), 0) as omzet')
            )
            ->groupBy('umkms.id', 'umkms.nama_umkm', 'umkms.status_verifikasi')
            ->orderBy('omzet')
            ->limit(5)
            ->get();

        $omzetBulanIni = (clone $orderQuery)
            ->whereMonth('tanggal_order', now()->month)
            ->whereYear('tanggal_order', now()->year)
            ->sum('total_harga');

        $omzetBulanLalu = (clone $orderQuery)
            ->whereMonth('tanggal_order', now()->subMonth()->month)
            ->whereYear('tanggal_order', now()->subMonth()->year)
            ->sum('total_harga');

        $pertumbuhanOmzet = $omzetBulanLalu > 0
            ? (($omzetBulanIni - $omzetBulanLalu) / $omzetBulanLalu) * 100
            : ($omzetBulanIni > 0 ? 100 : 0);

        $umkmTerverifikasi = DB::table('umkms')->where('status_verifikasi', 'verified')->count();
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0;

        $performaSektoral = DB::table('umkms')
            ->leftJoin('products', 'umkms.id', '=', 'products.umkm_id')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->select(
                DB::raw("COALESCE(umkms.kategori_usaha, 'Belum Dikategorikan') as sektor"),
                DB::raw('COUNT(DISTINCT umkms.id) as total_umkm'),
                DB::raw('COUNT(DISTINCT products.id) as total_produk'),
                DB::raw('COUNT(DISTINCT orders.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(order_details.subtotal), 0) as omzet')
            )
            ->groupBy('sektor')
            ->orderByDesc('omzet')
            ->limit(6)
            ->get();

        $kebijakanPrioritas = [
            [
                'judul' => 'Stimulus UMKM Mikro',
                'sasaran' => $rekomendasiUmkm->where('omzet', '<', 1000000)->count() . ' UMKM prioritas',
                'indikator' => 'Omzet rendah dan produk masih terbatas',
                'status' => 'Prioritas Tinggi',
            ],
            [
                'judul' => 'Akselerasi Legalitas',
                'sasaran' => $umkmPending . ' UMKM pending',
                'indikator' => 'Verifikasi dan kelengkapan data usaha',
                'status' => $umkmPending > 0 ? 'Perlu Tindak Lanjut' : 'Terkendali',
            ],
            [
                'judul' => 'Penguatan Sektor Unggulan',
                'sasaran' => optional($performaSektoral->first())->sektor ?? 'Belum ada sektor',
                'indikator' => 'Sektor dengan omzet tertinggi',
                'status' => 'Siap Dikembangkan',
            ],
        ];

        $indikatorEkonomi = [
            'umkm_terverifikasi' => $umkmTerverifikasi,
            'rasio_verifikasi' => $jumlahUmkm > 0 ? ($umkmTerverifikasi / $jumlahUmkm) * 100 : 0,
            'omzet_bulan_ini' => $omzetBulanIni,
            'pertumbuhan_omzet' => $pertumbuhanOmzet,
            'rata_rata_transaksi' => $rataRataTransaksi,
            'sektor_aktif' => $performaSektoral->count(),
        ];

        $stokMenipis = DB::table('products')
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('umkm_id', $umkmIds))
            ->where('stok_manual', '<=', 10)
            ->count();

        $stokKosong = DB::table('products')
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('umkm_id', $umkmIds))
            ->where('stok_manual', 0)
            ->count();

        $nilaiStok = DB::table('products')
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('umkm_id', $umkmIds))
            ->selectRaw('COALESCE(SUM(harga * stok_manual), 0) as total')
            ->value('total');

        $produkTerlarisUmkm = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.nama_produk',
                'products.stok_manual',
                DB::raw('SUM(order_details.jumlah) as total_terjual'),
                DB::raw('SUM(order_details.subtotal) as omzet')
            )
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('products.umkm_id', $umkmIds))
            ->groupBy('products.id', 'products.nama_produk', 'products.stok_manual')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $transaksiTerbaruUmkm = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('users', 'orders.buyer_id', '=', 'users.id')
            ->select(
                'orders.id as order_id',
                'orders.tanggal_order',
                'orders.status_order',
                'users.name as pembeli',
                'products.nama_produk',
                'order_details.jumlah',
                'order_details.subtotal'
            )
            ->when($umkmIds !== null, fn ($query) => $query->whereIn('products.umkm_id', $umkmIds))
            ->latest('orders.tanggal_order')
            ->limit(5)
            ->get();

        $pesananPerluDiproses = DB::table('orders')
            ->where('orders.status_order', 'paid')
            ->when($umkmIds !== null, function ($query) use ($umkmIds) {
                $query->whereIn('orders.id', function ($subquery) use ($umkmIds) {
                    $subquery->select('order_details.order_id')
                        ->from('order_details')
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->whereIn('products.umkm_id', $umkmIds);
                });
            })
            ->count();

        return view('dashboard', compact(
            'jumlahUmkm',
            'totalTransaksi',
            'totalOmzet',
            'totalProduk',
            'umkmPending',
            'totalKategori',
            'umkms',
            'grafikOmzet',
            'grafikTransaksi',
            'kategoriTerlaris',
            'pertumbuhanUmkm',
            'rekomendasiUmkm',
            'performaSektoral',
            'kebijakanPrioritas',
            'indikatorEkonomi',
            'stokMenipis',
            'stokKosong',
            'nilaiStok',
            'produkTerlarisUmkm',
            'transaksiTerbaruUmkm',
            'pesananPerluDiproses'
        ));
    }
}
