<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Simpanan extends Model
{
    protected $table = 'simpanan';
    protected $primaryKey = 'id_simpanan';

    protected $fillable = [
        'id_anggota',
        'id_jenis_simpanan',
        'nominal',
        'keterangan',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function jenisSimpanan(): BelongsTo
    {
        return $this->belongsTo(JenisSimpanan::class, 'id_jenis_simpanan', 'id_jenis_simpanan');
    }
}
