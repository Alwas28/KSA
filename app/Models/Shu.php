<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shu extends Model
{
    protected $table      = 'shu';
    protected $primaryKey = 'id_shu';

    protected $fillable = [
        'tahun',
        'total_shu',
        'alokasi_anggota',
        'alokasi_cadangan',
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(ShuDetail::class, 'id_shu', 'id_shu');
    }
}
