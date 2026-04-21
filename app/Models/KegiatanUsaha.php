<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanUsaha extends Model
{
    protected $table = 'kegiatan_usaha';
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'status'
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiKegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function getTotalPemasukanAttribute()
    {
        return $this->transaksi()->where('jenis_transaksi', 'pemasukan')->sum('nominal');
    }

    public function getTotalPengeluaranAttribute()
    {
        return $this->transaksi()->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
    }

    public function getSaldoAttribute()
    {
        return $this->total_pemasukan - $this->total_pengeluaran;
    }
}
