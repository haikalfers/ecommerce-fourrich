<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan', 30)->unique(); // contoh: ORD-20260905-0001
            $table->foreignId('toko_id')->constrained('toko')->restrictOnDelete();

            // Pembeli: bisa user terdaftar atau guest
            $table->foreignId('pembeli_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_guest', 100)->nullable();   // untuk guest
            $table->string('hp_guest', 20)->nullable();      // untuk guest

            // Detail pengiriman
            $table->enum('metode_kirim', ['cod', 'antar'])->default('cod');
            $table->text('alamat_kirim')->nullable();        // untuk metode antar

            // Pembayaran
            $table->enum('metode_bayar', ['cash', 'qris'])->default('cash');
            $table->enum('status_bayar', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamp('bayar_dikonfirmasi_at')->nullable();

            // Status pesanan
            $table->enum('status', ['pending', 'dikonfirmasi', 'diproses', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('alasan_batal')->nullable();
            $table->timestamp('selesai_at')->nullable();

            // Harga
            $table->decimal('subtotal', 15, 2);             // total sebelum ongkir
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2);

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('toko_id');
            $table->index('pembeli_id');
            $table->index('status');
            $table->index('kode_pesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
