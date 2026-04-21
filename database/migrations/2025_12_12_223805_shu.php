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
        Schema::create('shu', function (Blueprint $table) {
            $table->id('id_shu');
            $table->integer('tahun');
            $table->bigInteger('total_shu');
            $table->bigInteger('alokasi_anggota');
            $table->bigInteger('alokasi_cadangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shu');
    }
};