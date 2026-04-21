<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('kategori', ['Berita', 'Pengumuman', 'Artikel'])->default('Berita');
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar_url', 500)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('id_penulis')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
