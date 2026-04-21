<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiUmum extends Model
{
    protected $table = 'transaksi_umum';
    protected $primaryKey = 'id_transaksi_umum';

    protected $fillable = [
        'tanggal',
        'tipe',
        'kategori',
        'nominal',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
