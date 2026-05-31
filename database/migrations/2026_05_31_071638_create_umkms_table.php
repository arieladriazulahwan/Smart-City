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
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_umkm');
            $table->text('alamat');
            $table->string('kategori_usaha')->nullable();

            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();

            $table->enum('status_verifikasi',['pending','verified'])
                ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
