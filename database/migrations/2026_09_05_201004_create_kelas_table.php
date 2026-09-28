<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50);       // contoh: "XI PPLG 1"
            $table->string('jurusan', 100);          // contoh: "PPLG", "DKV", "Mekatronika"
            $table->string('tahun_ajaran', 10);      // contoh: "2025/2026"
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tahun_ajaran', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
