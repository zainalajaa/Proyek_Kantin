<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu');                 // waktu transaksi
            $table->integer('total_harga');            // total seluruh item
            $table->enum('metode_pembayaran', ['tunai', 'qris']);
            $table->string('status')->default('sukses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
