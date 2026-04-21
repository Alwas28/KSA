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
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id('id_simpanan');
            $table->unsignedBigInteger('id_anggota');
            $table->unsignedBigInteger('id_jenis_simpanan');
            $table->bigInteger('nominal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Kunci Asing (Foreign Keys)
            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
            $table->foreign('id_jenis_simpanan')->references('id_jenis_simpanan')->on('jenis_simpanan')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};