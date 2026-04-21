<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';
    protected $primaryKey = 'id_pinjaman';

    protected $fillable = [
        'id_anggota',
        'tanggal_pengajuan',
        'tanggal_acc',
        'tanggal_pencairan',
        'pokok_pinjaman',
        'lama_angsuran',
        'status',
        'status_angsuran',
        'sisa_angsuran',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_acc' => 'datetime',
        'tanggal_pencairan' => 'date',
        'pokok_pinjaman' => 'decimal:2',
        'sisa_angsuran' => 'integer',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function angsuran(): HasMany
    {
        return $this->hasMany(Angsuran::class, 'id_pinjaman', 'id_pinjaman');
    }

    // Hitung total pinjaman (hanya pokok, tanpa bunga)
    public function getTotalPinjamanAttribute()
    {
        return $this->pokok_pinjaman;
    }

    // Hitung angsuran per bulan (pokok pinjaman / lama angsuran)
    public function getAngsuranPerBulanAttribute()
    {
        return $this->lama_angsuran > 0 ? $this->pokok_pinjaman / $this->lama_angsuran : 0;
    }

    // Hitung nominal sisa angsuran (dalam rupiah)
    public function getNominalSisaAngsuranAttribute()
    {
        return $this->sisa_angsuran * $this->angsuran_per_bulan;
    }

    // Generate jadwal angsuran otomatis saat pinjaman dicairkan
    public function generateJadwalAngsuran()
    {
        // Hanya generate jika tanggal_pencairan ada dan belum ada angsuran
        if (!$this->tanggal_pencairan || $this->angsuran()->count() > 0) {
            return false;
        }

        $angsuranPerBulan = $this->angsuran_per_bulan;

        // Buat angsuran untuk setiap bulan
        for ($i = 1; $i <= $this->lama_angsuran; $i++) {
            Angsuran::create([
                'id_pinjaman' => $this->id_pinjaman,
                'angsuran_ke' => $i,
                'tanggal_jatuh_tempo' => Carbon::parse($this->tanggal_pencairan)->addMonths($i),
                'nominal_angsuran' => $angsuranPerBulan,
                'status' => 'belum_bayar'
            ]);
        }

        // Update status angsuran dan sisa angsuran
        $this->update([
            'status_angsuran' => 'aktif',
            'sisa_angsuran' => $this->lama_angsuran
        ]);

        return true;
    }

    // Update sisa angsuran
    public function updateSisaAngsuran()
    {
        $sisaBelumBayar = $this->angsuran()->where('status', 'belum_bayar')->count();

        $this->update([
            'sisa_angsuran' => $sisaBelumBayar
        ]);

        // Jika semua sudah dibayar, update status_angsuran menjadi selesai
        if ($sisaBelumBayar === 0 && $this->angsuran()->count() > 0) {
            $this->update([
                'status_angsuran' => 'selesai'
            ]);
        }
    }
}
