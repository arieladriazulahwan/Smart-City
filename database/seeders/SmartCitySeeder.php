<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SmartCitySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedUsers();
            $this->seedCategories();
            $this->seedUmkms();
            $this->seedProducts();
            $this->seedOrders();
        });
    }

    private function seedUsers(): void
    {
        $users = [
            ['name' => 'Admin Smart City', 'email' => 'admin@umkm.com', 'role' => 'admin'],
            ['name' => 'Pemerintah Kota Palu', 'email' => 'pemerintah@umkm.com', 'role' => 'pemerintah'],
            ['name' => 'Kopi Lokal Palu', 'email' => 'kopi@umkm.com', 'role' => 'umkm'],
            ['name' => 'Tenun Kaili Palu', 'email' => 'tenun@umkm.com', 'role' => 'umkm'],
            ['name' => 'Sambal Roa Talise', 'email' => 'sambal@umkm.com', 'role' => 'umkm'],
            ['name' => 'Laundry Bersih Palu', 'email' => 'laundry@umkm.com', 'role' => 'umkm'],
            ['name' => 'Pembeli Demo', 'email' => 'pembeli@umkm.com', 'role' => 'pembeli'],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedCategories(): void
    {
        foreach (['Kuliner', 'Kerajinan', 'Fashion', 'Jasa'] as $category) {
            DB::table('categories')->updateOrInsert(
                ['nama_kategori' => $category],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    private function seedUmkms(): void
    {
        $umkms = [
            [
                'email' => 'kopi@umkm.com',
                'nama_umkm' => 'Kopi Lokal Palu',
                'alamat' => 'Jl. Dewi Sartika, Palu Selatan, Kota Palu',
                'kategori_usaha' => 'Kuliner',
                'latitude' => -0.9089600,
                'longitude' => 119.8705400,
                'status_verifikasi' => 'verified',
            ],
            [
                'email' => 'tenun@umkm.com',
                'nama_umkm' => 'Tenun Kaili Palu',
                'alamat' => 'Jl. Setia Budi, Palu Timur, Kota Palu',
                'kategori_usaha' => 'Kerajinan',
                'latitude' => -0.8954300,
                'longitude' => 119.8809800,
                'status_verifikasi' => 'verified',
            ],
            [
                'email' => 'sambal@umkm.com',
                'nama_umkm' => 'Sambal Roa Talise',
                'alamat' => 'Kawasan Talise, Mantikulore, Kota Palu',
                'kategori_usaha' => 'Kuliner',
                'latitude' => -0.8756200,
                'longitude' => 119.8867100,
                'status_verifikasi' => 'verified',
            ],
            [
                'email' => 'laundry@umkm.com',
                'nama_umkm' => 'Laundry Bersih Palu',
                'alamat' => 'Jl. Basuki Rahmat, Palu Selatan, Kota Palu',
                'kategori_usaha' => 'Jasa',
                'latitude' => -0.9202800,
                'longitude' => 119.8579200,
                'status_verifikasi' => 'pending',
            ],
        ];

        foreach ($umkms as $umkm) {
            $userId = DB::table('users')->where('email', $umkm['email'])->value('id');

            DB::table('umkms')->updateOrInsert(
                ['nama_umkm' => $umkm['nama_umkm']],
                [
                    'user_id' => $userId,
                    'alamat' => $umkm['alamat'],
                    'kategori_usaha' => $umkm['kategori_usaha'],
                    'latitude' => $umkm['latitude'],
                    'longitude' => $umkm['longitude'],
                    'status_verifikasi' => $umkm['status_verifikasi'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedProducts(): void
    {
        $products = [
            ['umkm' => 'Kopi Lokal Palu', 'category' => 'Kuliner', 'nama_produk' => 'Kopi Bubuk Palu', 'harga' => 35000, 'stok_manual' => 48, 'status_stok' => 'Menipis', 'deskripsi' => 'Kopi bubuk khas Palu dengan aroma kuat.'],
            ['umkm' => 'Kopi Lokal Palu', 'category' => 'Kuliner', 'nama_produk' => 'Keripik Pisang Palu', 'harga' => 20000, 'stok_manual' => 76, 'status_stok' => 'Aman', 'deskripsi' => 'Keripik pisang renyah produksi UMKM lokal.'],
            ['umkm' => 'Tenun Kaili Palu', 'category' => 'Kerajinan', 'nama_produk' => 'Kain Tenun Motif Kaili', 'harga' => 185000, 'stok_manual' => 12, 'status_stok' => 'Aman', 'deskripsi' => 'Kain tenun lokal dengan motif khas Kaili.'],
            ['umkm' => 'Tenun Kaili Palu', 'category' => 'Fashion', 'nama_produk' => 'Tas Tenun Mini', 'harga' => 95000, 'stok_manual' => 8, 'status_stok' => 'Menipis', 'deskripsi' => 'Tas mini berbahan kain tenun lokal.'],
            ['umkm' => 'Sambal Roa Talise', 'category' => 'Kuliner', 'nama_produk' => 'Sambal Roa Botol', 'harga' => 30000, 'stok_manual' => 34, 'status_stok' => 'Aman', 'deskripsi' => 'Sambal roa pedas gurih khas pesisir Talise.'],
            ['umkm' => 'Laundry Bersih Palu', 'category' => 'Jasa', 'nama_produk' => 'Paket Laundry 5 Kg', 'harga' => 45000, 'stok_manual' => 100, 'status_stok' => 'Aman', 'deskripsi' => 'Layanan cuci lipat pakaian untuk area Palu Selatan.'],
        ];

        foreach ($products as $product) {
            $umkmId = DB::table('umkms')->where('nama_umkm', $product['umkm'])->value('id');
            $categoryId = DB::table('categories')->where('nama_kategori', $product['category'])->value('id');

            DB::table('products')->updateOrInsert(
                ['umkm_id' => $umkmId, 'nama_produk' => $product['nama_produk']],
                [
                    'category_id' => $categoryId,
                    'harga' => $product['harga'],
                    'stok_manual' => $product['stok_manual'],

                    'status_stok' => $product['status_stok'],
                    'gambar' => null,
                    'deskripsi' => $product['deskripsi'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedOrders(): void
    {
        $buyerId = DB::table('users')->where('email', 'pembeli@umkm.com')->value('id');
        $demoOrderIds = DB::table('orders')->where('buyer_id', $buyerId)->pluck('id');

        if ($demoOrderIds->isNotEmpty()) {
            DB::table('order_details')->whereIn('order_id', $demoOrderIds)->delete();
            DB::table('orders')->whereIn('id', $demoOrderIds)->delete();
        }

        $orders = [
            [
                'tanggal_order' => Carbon::now()->subMonths(2),
                'status_order' => 'completed',
                'items' => [
                    ['product' => 'Kopi Bubuk Palu', 'jumlah' => 2],
                    ['product' => 'Sambal Roa Botol', 'jumlah' => 1],
                ],
            ],
            [
                'tanggal_order' => Carbon::now()->subMonth(),
                'status_order' => 'completed',
                'items' => [
                    ['product' => 'Keripik Pisang Palu', 'jumlah' => 6],
                    ['product' => 'Tas Tenun Mini', 'jumlah' => 1],
                ],
            ],
            [
                'tanggal_order' => Carbon::now(),
                'status_order' => 'paid',
                'items' => [
                    ['product' => 'Kain Tenun Motif Kaili', 'jumlah' => 1],
                    ['product' => 'Paket Laundry 5 Kg', 'jumlah' => 2],
                ],
            ],
        ];

        foreach ($orders as $order) {
            $total = 0;
            $details = [];

            foreach ($order['items'] as $item) {
                $product = DB::table('products')->where('nama_produk', $item['product'])->first();
                $subtotal = $product->harga * $item['jumlah'];
                $total += $subtotal;
                $details[] = compact('product', 'item', 'subtotal');
            }

            $orderId = DB::table('orders')->insertGetId([
                'buyer_id' => $buyerId,
                'total_harga' => $total,
                'status_order' => $order['status_order'],
                'tanggal_order' => $order['tanggal_order'],
                'created_at' => $order['tanggal_order'],
                'updated_at' => $order['tanggal_order'],
            ]);

            foreach ($details as $detail) {
                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_id' => $detail['product']->id,
                    'jumlah' => $detail['item']['jumlah'],
                    'harga_satuan' => $detail['product']->harga,
                    'subtotal' => $detail['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }


}
