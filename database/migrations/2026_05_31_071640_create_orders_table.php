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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->decimal('total_harga',12,2);

            $table->enum('status_order',[
                'pending',
                'paid',
                'completed'
            ])->default('pending');

            $table->dateTime('tanggal_order');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
