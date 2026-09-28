<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();

            // Snapshot data produk saat order (immutable)
            $table->string('nama_produk_snapshot', 200);
            $table->decimal('harga_snapshot', 15, 2);       // harga_final saat itu
            $table->decimal('harga_pokok_snapshot', 15, 2); // harga_pokok saat itu
            $table->decimal('fee_persen_snapshot', 5, 2);   // fee % kategori saat itu

            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);             // harga_snapshot × jumlah

            // Fee split (dihitung saat order selesai/dikonfirmasi)
            $table->decimal('fee_admin', 15, 2)->default(0);   // bagian sekolah
            $table->decimal('fee_toko', 15, 2)->default(0);    // laba toko
            $table->boolean('fee_sudah_dicatat')->default(false);

            $table->timestamps();

            $table->index('pesanan_id');
            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
