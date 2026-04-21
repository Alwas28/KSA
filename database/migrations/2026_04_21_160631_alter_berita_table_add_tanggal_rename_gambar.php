<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'gambar_url')) {
                $table->renameColumn('gambar_url', 'gambar');
            }
            if (!Schema::hasColumn('berita', 'tanggal')) {
                $table->date('tanggal')->default(now()->toDateString())->after('gambar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'gambar')) {
                $table->renameColumn('gambar', 'gambar_url');
            }
            if (Schema::hasColumn('berita', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
        });
    }
};
