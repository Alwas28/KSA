<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKegiatan extends Model
{
    protected $table = 'transaksi_kegiatan';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_kegiatan',
        'tanggal_transaksi',
        'jenis_transaksi',
        'nominal',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanUsaha::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
