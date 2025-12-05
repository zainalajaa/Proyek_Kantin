<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id();

            // relasi ke penjualan (OK)
            $table->foreignId('id_penjualan')
                ->constrained('penjualan')
                ->onDelete('cascade');

            // relasi ke produk (MANUAL, karena pk = id_produk)
            $table->unsignedBigInteger('id_produk');

            $table->foreign('id_produk')
                ->references('id_produk')  // <- kolom di tabel produk
                ->on('produk')
                ->onDelete('cascade');

            $table->string('nama_produk');
            $table->integer('harga_satuan');
            $table->integer('jumlah')->default(1);
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
