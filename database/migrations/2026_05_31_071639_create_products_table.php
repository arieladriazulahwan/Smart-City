<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->foreignId('umkm_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('nama_produk');

            $table->decimal('harga',12,2);

            $table->integer('stok_manual')->default(0);

            $table->integer('stok_iot')->default(0);

            $table->string('status_stok')
                ->default('aman');

            $table->string('gambar')->nullable();

            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
