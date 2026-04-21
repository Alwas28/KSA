<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusAnggota extends Model
{
    protected $table = 'status_anggota';
    protected $primaryKey = 'id_status_anggota';

    protected $fillable = [
        'nama_status',
        'deskripsi',
    ];

    public function anggota(): HasMany
    {
        return $this->hasMany(Anggota::class, 'id_status_anggota', 'id_status_anggota');
    }
}
