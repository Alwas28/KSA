<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->date('tanggal_pencairan')->nullable()->after('tanggal_acc');
            $table->enum('status_angsuran', ['belum_aktif', 'aktif', 'selesai', 'macet'])->default('belum_aktif')->after('status');
            $table->integer('sisa_angsuran')->default(0)->after('status_angsuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pencairan', 'status_angsuran', 'sisa_angsuran']);
        });
    }
};
