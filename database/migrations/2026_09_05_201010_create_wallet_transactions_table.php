<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('toko')->cascadeOnDelete();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanan')->nullOnDelete();
            $table->enum('tipe', ['kredit', 'debit']);      // kredit = masuk, debit = keluar
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->string('keterangan', 255);
            $table->enum('kategori', [
                'penjualan',        // dari pesanan selesai
                'penarikan',        // withdraw ke rekening
                'penyesuaian',      // koreksi manual admin
                'refund',           // pengembalian dana
            ])->default('penjualan');
            $table->string('referensi', 100)->nullable();   // nomor referensi eksternal
            $table->timestamps();

            $table->index('toko_id');
            $table->index('tipe');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
