<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'no_anggota',
        'nama',
        'email',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'id_jenis_anggota',
        'aktif',
        'id_status_anggota',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function statusAnggota(): BelongsTo
    {
        return $this->belongsTo(StatusAnggota::class, 'id_status_anggota', 'id_status_anggota');
    }

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class, 'id_anggota', 'id_anggota');
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class, 'id_anggota', 'id_anggota');
    }
}
