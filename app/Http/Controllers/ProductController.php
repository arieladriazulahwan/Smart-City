<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function statusStok($stok): string
    {
        return $stok <= 10 ? 'Menipis' : 'Aman';
    }

    private function ownedUmkmIds()
    {
        return DB::table('umkms')->where('user_id', Auth::id())->pluck('id');
    }

    public function index(Request $request)
    {
        $products = DB::table('products')
            ->join('umkms', 'products.umkm_id', '=', 'umkms.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.*',
                'umkms.nama_umkm',
                'categories.nama_kategori'
            )
            ->where('umkms.status_verifikasi', 'verified')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('products.nama_produk', 'like', '%' . $search . '%')
                        ->orWhere('umkms.nama_umkm', 'like', '%' . $search . '%');
                });
            })
            ->when($request->category_id, fn ($query, $categoryId) => $query->where('products.category_id', $categoryId))
            ->orderBy('products.nama_produk')
            ->paginate(12);

        $categories = DB::table('categories')->orderBy('nama_kategori')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function umkmProducts()
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'umkm']), 403);

        $products = DB::table('products')
            ->join('umkms', 'products.umkm_id', '=', 'umkms.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.*',
                'umkms.nama_umkm',
                'categories.nama_kategori'
            )
            ->when(Auth::user()->role === 'umkm', fn ($query) => $query->whereIn('products.umkm_id', $this->ownedUmkmIds()))
            ->orderBy('products.nama_produk')
            ->get();

        return view('umkm.products.index', compact('products'));
    }

    public function create()
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'umkm']), 403);

        $categories = DB::table('categories')->orderBy('nama_kategori')->get();
        $umkms = DB::table('umkms')
            ->when(Auth::user()->role === 'umkm', fn ($query) => $query->where('user_id', Auth::id()))
            ->orderBy('nama_umkm')
            ->get();

        return view('umkm.products.create', compact('categories', 'umkms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'umkm_id' => 'required|exists:umkms,id',
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_manual' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable',
        ]);

        $product = DB::table('products')->where('id', $id)->first();
        abort_if(!$product, 404);

        if (Auth::user()->role === 'umkm') {
            $ownedUmkmIds = $this->ownedUmkmIds();
            abort_unless($ownedUmkmIds->contains((int) $product->umkm_id), 403);
            abort_unless($ownedUmkmIds->contains((int) $request->umkm_id), 403);
        }

        $gambar = $request->hasFile('gambar')
            ? $request->file('gambar')->store('products', 'public')
            : null;

        DB::table('products')->insert([
            'umkm_id' => $request->umkm_id,
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok_manual' => $request->stok_manual,
            'stok_iot' => 0,
            'status_stok' => $this->statusStok((int) $request->stok_manual),
            'gambar' => $gambar,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/umkm/produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        abort_if(!$product, 404);

        if (Auth::user()->role === 'umkm') {
            abort_unless($this->ownedUmkmIds()->contains((int) $product->umkm_id), 403);
        }

        $categories = DB::table('categories')->orderBy('nama_kategori')->get();
        $umkms = DB::table('umkms')
            ->when(Auth::user()->role === 'umkm', fn ($query) => $query->where('user_id', Auth::id()))
            ->orderBy('nama_umkm')
            ->get();

        return view('umkm.products.edit', compact('product', 'categories', 'umkms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'umkm_id' => 'required|exists:umkms,id',
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_manual' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable',
        ]);

        if (Auth::user()->role === 'umkm') {
            abort_unless($this->ownedUmkmIds()->contains((int) $request->umkm_id), 403);
        }

        $payload = [
            'umkm_id' => $request->umkm_id,
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok_manual' => $request->stok_manual,
            'status_stok' => $this->statusStok((int) $request->stok_manual),
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ];

        if ($request->hasFile('gambar')) {
            if ($product?->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $payload['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        DB::table('products')->where('id', $id)->update($payload);

        return redirect('/umkm/produk')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        abort_if(!$product, 404);

        if (Auth::user()->role === 'umkm') {
            abort_unless($this->ownedUmkmIds()->contains((int) $product->umkm_id), 403);
        }

        DB::table('products')->where('id', $id)->delete();

        return redirect('/umkm/produk')->with('success', 'Produk berhasil dihapus.');
    }

    public function transactions()
    {
        abort_unless(Auth::user()->role === 'umkm', 403);

        $orders = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('users', 'orders.buyer_id', '=', 'users.id')
            ->select(
                'orders.id as order_id',
                'orders.tanggal_order',
                'orders.total_harga',
                'orders.status_order',
                'users.name as pembeli',
                'products.nama_produk',
                'order_details.jumlah',
                'order_details.subtotal'
            )
            ->whereIn('products.umkm_id', $this->ownedUmkmIds())
            ->latest('orders.tanggal_order')
            ->get();

        $ringkasan = [
            'total_pesanan' => $orders->pluck('order_id')->unique()->count(),
            'total_item' => $orders->sum('jumlah'),
            'total_omzet' => $orders->sum('subtotal'),
            'menunggu_selesai' => $orders->where('status_order', 'paid')->pluck('order_id')->unique()->count(),
        ];

        return view('umkm.transactions.index', compact('orders', 'ringkasan'));
    }
}
