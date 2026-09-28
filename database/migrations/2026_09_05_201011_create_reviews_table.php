<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('rating');              // 1–5
            $table->text('ulasan')->nullable();
            $table->json('foto')->nullable();           // foto review dari pembeli
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->unique(['pesanan_id', 'produk_id']); // 1 review per produk per pesanan
            $table->index('produk_id');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
