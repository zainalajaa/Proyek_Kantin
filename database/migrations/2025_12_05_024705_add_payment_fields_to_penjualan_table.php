<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->integer('paid_amount')->nullable()->after('total_harga');
            $table->timestamp('paid_at')->nullable()->after('paid_amount');
            // status sudah ada, pastikan default tidak selalu 'sukses' untuk tunai flow
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'paid_at']);
        });
    }
};
