<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('toko')->cascadeOnDelete();
            $table->enum('jenis_award', [
                'toko_terlaris',    // 🥇 omzet tertinggi bulan ini
                'best_newcomer',    // 🚀 toko baru pertumbuhan tercepat
                'most_reviewed',    // ⭐ produk rating terbanyak
                'most_consistent',  // 📦 paling rutin update produk
                'top_per_kelas',    // 🏆 terlaris di kelas masing-masing
            ]);
            $table->integer('bulan');                   // 1–12
            $table->integer('tahun');
            $table->decimal('nilai_pencapaian', 15, 2)->nullable(); // omzet / jumlah review / dll
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->unique(['toko_id', 'jenis_award', 'bulan', 'tahun']);
            $table->index(['bulan', 'tahun']);
            $table->index('jenis_award');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
