<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    protected $primaryKey = 'id_angsuran';

    protected $fillable = [
        'id_pinjaman',
        'angsuran_ke',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'nominal_angsuran',
        'nominal_dibayar',
        'keterangan',
        'status',
        'dibayar_oleh',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman', 'id_pinjaman');
    }

    public function pembayar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibayar_oleh', 'id');
    }

    // Check if this angsuran is late
    public function getIsLateAttribute()
    {
        if ($this->status === 'dibayar') {
            return false;
        }
        return Carbon::now()->isAfter($this->tanggal_jatuh_tempo);
    }

    // Calculate late days
    public function getHariTerlatAttribute()
    {
        if (!$this->is_late) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->tanggal_jatuh_tempo);
    }
}
