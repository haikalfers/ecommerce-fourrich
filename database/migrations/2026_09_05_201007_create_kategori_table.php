<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();            // emoji atau nama icon
            $table->string('warna', 7)->nullable();        // hex color untuk UI
            $table->decimal('fee_persen', 5, 2)->default(0); // persentase fee (0-100)
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);         // urutan tampil di katalog
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
