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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id('id_pinjaman');
            $table->unsignedBigInteger('id_anggota');
            $table->dateTime('tanggal_pengajuan');
            $table->dateTime('tanggal_acc')->nullable();
            $table->bigInteger('pokok_pinjaman');
            $table->integer('bunga_persen');
            $table->integer('lama_angsuran');
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'lunas'])->default('diajukan');
            $table->timestamps();

            // Kunci Asing (Foreign Key)
            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};