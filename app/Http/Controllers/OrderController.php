<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function orderRows($query)
    {
        return $query
            ->join('users', 'orders.buyer_id', '=', 'users.id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('umkms', 'products.umkm_id', '=', 'umkms.id')
            ->select(
                'orders.id',
                'orders.tanggal_order',
                'orders.total_harga',
                'orders.status_order',
                'users.name as pembeli',
                'products.nama_produk',
                'umkms.nama_umkm',
                'order_details.jumlah',
                'order_details.subtotal'
            )
            ->latest('orders.tanggal_order')
            ->get();
    }

    public function buyerOrders()
    {
        abort_unless(Auth::user()->role === 'pembeli', 403);

        $orders = $this->orderRows(
            DB::table('orders')->where('orders.buyer_id', Auth::id())
        );

        return view('orders.buyer', compact('orders'));
    }

    public function adminOrders()
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'pemerintah']), 403);

        $orders = $this->orderRows(DB::table('orders'));

        return view('orders.admin', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        abort_unless(Auth::user()->role === 'umkm', 403);

        $request->validate([
            'status_order' => 'required|in:paid,completed',
        ]);

        $ownsOrder = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('umkms', 'products.umkm_id', '=', 'umkms.id')
            ->where('order_details.order_id', $id)
            ->where('umkms.user_id', Auth::id())
            ->exists();

        abort_unless($ownsOrder, 403);

        DB::table('orders')->where('id', $id)->update([
            'status_order' => $request->status_order,
            'updated_at' => now(),
        ]);

        return redirect('/umkm/transaksi')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
