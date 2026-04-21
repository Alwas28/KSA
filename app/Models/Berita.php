<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul', 'slug', 'kategori', 'ringkasan',
        'konten', 'gambar', 'tanggal', 'status', 'id_penulis',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function penulis()
    {
        return $this->belongsTo(User::class, 'id_penulis');
    }

    public static function generateSlug(string $judul): string
    {
        $base  = Str::slug($judul);
        $slug  = $base;
        $i     = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function kategoriColor(): string
    {
        return match ($this->kategori) {
            'Pengumuman' => 'red',
            'Artikel'    => 'purple',
            default      => 'blue',
        };
    }
}
