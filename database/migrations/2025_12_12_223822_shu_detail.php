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
        Schema::create('shu_detail', function (Blueprint $table) {
            $table->id('id_shu_detail');
            $table->unsignedBigInteger('id_shu');
            $table->unsignedBigInteger('id_anggota');
            $table->bigInteger('jumlah');
            $table->timestamps();

            // Kunci Asing (Foreign Keys)
            $table->foreign('id_shu')->references('id_shu')->on('shu')->onDelete('cascade');
            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');

            // Agar tidak ada duplikasi data (satu anggota, satu detail SHU per tahun)
            $table->unique(['id_shu', 'id_anggota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shu_detail');
    }
};