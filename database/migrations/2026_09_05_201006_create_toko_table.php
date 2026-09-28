<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('co_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_toko', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', ['pending', 'aktif', 'suspend'])->default('pending');
            $table->text('alasan_suspend')->nullable();
            $table->decimal('wallet_saldo', 15, 2)->default(0);
            $table->string('no_rekening', 50)->nullable();
            $table->string('nama_bank', 50)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('co_admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toko');
    }
};
