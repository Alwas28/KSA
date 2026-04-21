<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    protected $table = 'jenis_simpanan';
    protected $primaryKey = 'id_jenis_simpanan';

    protected $fillable = [
        'nama_jenis',
        'deskripsi',
    ];
}
