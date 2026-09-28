<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini menambahkan FK ke users.co_admin_id (self-ref) dan
// users.kelas_id → kelas, setelah tabel kelas sudah ada.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('co_admin_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['co_admin_id']);
            $table->dropForeign(['kelas_id']);
        });
    }
};
