<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private function ensureAdmin()
    {
        abort_unless(Auth::user()->role === 'admin', 403);
    }

    public function index()
    {
        $this->ensureAdmin();

        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.*', DB::raw('COUNT(products.id) as total_produk'))
            ->groupBy('categories.id', 'categories.nama_kategori', 'categories.created_at', 'categories.updated_at')
            ->orderBy('categories.nama_kategori')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ]);

        DB::table('categories')->insert([
            'nama_kategori' => $request->nama_kategori,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->ensureAdmin();

        $category = DB::table('categories')->where('id', $id)->first();
        abort_if(!$category, 404);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $id,
        ]);

        DB::table('categories')->where('id', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'updated_at' => now(),
        ]);

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->ensureAdmin();

        DB::table('categories')->where('id', $id)->delete();

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}
