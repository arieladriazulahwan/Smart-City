<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $ids = array_keys($cart);
        $products = collect();

        if ($ids) {
            $products = DB::table('products')
                ->join('umkms', 'products.umkm_id', '=', 'umkms.id')
                ->select('products.*', 'umkms.nama_umkm')
                ->whereIn('products.id', $ids)
                ->get()
                ->map(function ($product) use ($cart) {
                    $product->jumlah = $cart[$product->id];
                    $product->subtotal = $product->jumlah * $product->harga;
                    return $product;
                });
        }

        $total = $products->sum('subtotal');

        return view('cart.index', compact('products', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        abort_if(!$product, 404);

        $request->validate([
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $cart = session('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + (int) ($request->jumlah ?? 1);
        session(['cart' => $cart]);

        return redirect('/keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id] = (int) $request->jumlah;
        }
        session(['cart' => $cart]);

        return redirect('/keranjang')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect('/keranjang')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        abort_if(empty($cart), 400);

        $products = DB::table('products')->whereIn('id', array_keys($cart))->get();
        foreach ($products as $product) {
            if ($cart[$product->id] > $product->stok_manual) {
                return redirect('/keranjang')->with('error', 'Stok ' . $product->nama_produk . ' tidak mencukupi.');
            }
        }

        $total = $products->sum(fn ($product) => $product->harga * $cart[$product->id]);
        $createdOrderId = null;

        DB::transaction(function () use ($products, $cart, $total, &$createdOrderId) {
            $createdOrderId = DB::table('orders')->insertGetId([
                'buyer_id' => Auth::id(),
                'total_harga' => $total,
                'status_order' => 'paid',
                'tanggal_order' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($products as $product) {
                $jumlah = $cart[$product->id];

                DB::table('order_details')->insert([
                    'order_id' => $createdOrderId,
                    'product_id' => $product->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $product->harga,
                    'subtotal' => $product->harga * $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $stokManual = max(0, $product->stok_manual - $jumlah);
                DB::table('products')->where('id', $product->id)->update([
                    'stok_manual' => $stokManual,
                    'status_stok' => $stokManual <= 10 ? 'Menipis' : 'Aman',
                    'updated_at' => now(),
                ]);
            }
        });

        session()->forget('cart');

        return redirect('/pesanan')->with('success', 'Checkout berhasil. Nomor pesanan #' . $createdOrderId . ' sudah tercatat.');
    }
}
