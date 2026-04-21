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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->string('no_anggota')->unique();
            $table->string('nama', 255);
            $table->string('email', 255)->unique();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir', 255)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['P', 'L'])->nullable(); // Pria, Wanita (Laki-laki)
            $table->string('pekerjaan', 255)->nullable();
            $table->unsignedBigInteger('id_status_anggota')->nullable(); // Misal: tabel jenis_anggota
            $table->enum('aktif', ['Y', 'N'])->default('Y');
            $table->timestamps();

            // Relasi ke tabel 'jenis_anggota' jika ada
            // $table->foreign('id_jenis_anggota')->references('id')->on('jenis_anggota')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};