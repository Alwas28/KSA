<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSimpanan extends Model
{
    protected $table = 'jenis_simpanan';
    protected $primaryKey = 'id_jenis_simpanan';

    protected $fillable = [
        'nama_jenis',
        'deskripsi',
    ];

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class, 'id_jenis_simpanan', 'id_jenis_simpanan');
    }
}
