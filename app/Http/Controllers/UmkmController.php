<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UmkmController extends Controller
{
    private function canManage($umkm = null): bool
    {
        $role = Auth::user()->role;

        if (in_array($role, ['admin', 'pemerintah'])) {
            return true;
        }

        return $role === 'umkm' && (!$umkm || $umkm->user_id === Auth::id());
    }

    public function index()
    {
        $query = DB::table('umkms')
            ->join('users', 'umkms.user_id', '=', 'users.id')
            ->leftJoin('products', 'umkms.id', '=', 'products.umkm_id')
            ->select(
                'umkms.*',
                'users.name',
                'users.email',
                DB::raw('COUNT(products.id) as total_produk')
            )
            ->groupBy(
                'umkms.id',
                'umkms.user_id',
                'umkms.nama_umkm',
                'umkms.alamat',
                'umkms.kategori_usaha',
                'umkms.latitude',
                'umkms.longitude',
                'umkms.status_verifikasi',
                'umkms.created_at',
                'umkms.updated_at',
                'users.name',
                'users.email'
            )
            ->orderBy('umkms.nama_umkm');

        if (Auth::user()->role === 'umkm') {
            $query->where('umkms.user_id', Auth::id());
        }

        $umkms = $query->get();

        return view('umkm.index', compact('umkms'));
    }

    public function create()
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'umkm']), 403);

        $users = DB::table('users')->where('role', 'umkm')->orderBy('name')->get();

        return view('umkm.create', compact('users'));
    }

    public function store(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'umkm']), 403);

        $request->validate([
            'user_id' => Auth::user()->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'nama_umkm' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kategori_usaha' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DB::table('umkms')->insert([
            'user_id' => Auth::user()->role === 'admin' ? $request->user_id : Auth::id(),
            'nama_umkm' => $request->nama_umkm,
            'alamat' => $request->alamat,
            'kategori_usaha' => $request->kategori_usaha,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status_verifikasi' => Auth::user()->role === 'admin' ? 'verified' : 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/umkm')->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $umkm = DB::table('umkms')->where('id', $id)->first();
        abort_if(!$umkm, 404);
        abort_unless($this->canManage($umkm), 403);

        $users = DB::table('users')->where('role', 'umkm')->orderBy('name')->get();

        return view('umkm.edit', compact('umkm', 'users'));
    }

    public function update(Request $request, $id)
    {
        $umkm = DB::table('umkms')->where('id', $id)->first();
        abort_if(!$umkm, 404);
        abort_unless($this->canManage($umkm), 403);

        $request->validate([
            'user_id' => Auth::user()->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'nama_umkm' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kategori_usaha' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DB::table('umkms')->where('id', $id)->update([
            'user_id' => Auth::user()->role === 'admin' ? $request->user_id : $umkm->user_id,
            'nama_umkm' => $request->nama_umkm,
            'alamat' => $request->alamat,
            'kategori_usaha' => $request->kategori_usaha,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
        ]);

        return redirect('/umkm')->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function verify($id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        DB::table('umkms')->where('id', $id)->update([
            'status_verifikasi' => 'verified',
            'updated_at' => now(),
        ]);

        return redirect('/umkm')->with('success', 'UMKM berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        DB::table('umkms')->where('id', $id)->delete();

        return redirect('/umkm')->with('success', 'Data UMKM berhasil dihapus.');
    }
}
