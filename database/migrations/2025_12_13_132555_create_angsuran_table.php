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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id('id_angsuran');
            $table->unsignedBigInteger('id_pinjaman');
            $table->integer('angsuran_ke');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->bigInteger('nominal_angsuran');
            $table->bigInteger('nominal_dibayar')->nullable();
            $table->bigInteger('denda')->default(0);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['belum_bayar', 'dibayar', 'telat'])->default('belum_bayar');
            $table->unsignedBigInteger('dibayar_oleh')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_pinjaman')->references('id_pinjaman')->on('pinjaman')->onDelete('cascade');
            $table->foreign('dibayar_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
