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
        Schema::create('transaksi_umum', function (Blueprint $table) {
            $table->id('id_transaksi_umum');
            $table->dateTime('tanggal');
            $table->enum('tipe', ['debit', 'kredit']); // Misal: 'debit' untuk pemasukan, 'kredit' untuk pengeluaran
            $table->string('kategori', 255)->nullable();
            $table->bigInteger('nominal');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // Misal: ID pengguna yang membuat transaksi
            $table->timestamps();

            // Relasi ke tabel 'users' untuk created_by jika ada
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_umum');
    }
};