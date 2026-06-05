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
            'rekomendasiUmkm'
        ));
    }
}
