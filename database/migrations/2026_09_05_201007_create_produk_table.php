<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('toko')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('nama_produk', 200);
            $table->string('slug', 220)->unique();
            $table->text('deskripsi')->nullable();
            $table->json('foto')->nullable();               // array of file paths
            $table->decimal('harga_pokok', 15, 2);         // harga modal (input siswa)
            $table->decimal('harga_final', 15, 2);         // harga jual = pokok + margin
            $table->decimal('harga_reseller', 15, 2)->nullable(); // harga khusus reseller (Fase 2)
            $table->integer('stok')->default(0);
            $table->string('satuan', 30)->default('pcs'); // pcs, pack, porsi, dll
            $table->boolean('is_reseller_product')->default(false); // produk dari toko sekolah
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_reject')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);   // bisa dinonaktifkan sementara
            $table->integer('total_terjual')->default(0);  // denormalized counter
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();

            $table->index('toko_id');
            $table->index('kategori_id');
            $table->index('status');
            $table->index(['status', 'is_active']);
            $table->fullText(['nama_produk', 'deskripsi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
