<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShuDetail extends Model
{
    protected $table      = 'shu_detail';
    protected $primaryKey = 'id_shu_detail';

    protected $fillable = [
        'id_shu',
        'id_anggota',
        'jumlah',
    ];

    public function shu(): BelongsTo
    {
        return $this->belongsTo(Shu::class, 'id_shu', 'id_shu');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }
}
