<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari anggota berdasarkan email user yang login
        $anggota = Anggota::where('email', $user->email)->first();

        if (!$anggota) {
            return view('riwayat-transaksi.index', [
                'anggota' => null,
                'transaksi' => collect([]),
                'totalSimpanan' => 0,
                'totalPenarikan' => 0,
                'totalPinjaman' => 0,
                'totalAngsuran' => 0,
            ]);
        }

        // Ambil semua transaksi simpanan
        $simpanan = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->with('jenisSimpanan')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->created_at,
                    'jenis' => 'Simpanan',
                    'kategori' => $item->jenisSimpanan->nama_jenis,
                    'keterangan' => 'Setoran ' . $item->jenisSimpanan->nama_jenis,
                    'debit' => $item->nominal,
                    'kredit' => 0,
                    'saldo' => 0, // akan dihitung nanti
                ];
            });

        // Ambil semua pencairan pinjaman
        $pinjaman = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->whereNotNull('tanggal_pencairan')
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_pencairan,
                    'jenis' => 'Pinjaman',
                    'kategori' => 'Pencairan Pinjaman',
                    'keterangan' => 'Pencairan pinjaman sebesar Rp ' . number_format($item->pokok_pinjaman, 0, ',', '.') . ' untuk ' . $item->lama_angsuran . ' bulan angsuran',
                    'debit' => $item->pokok_pinjaman,
                    'kredit' => 0,
                    'saldo' => 0,
                ];
            });

        // Ambil semua pembayaran angsuran
        $angsuran = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })
            ->where('status', 'dibayar')
            ->whereNotNull('tanggal_bayar')
            ->with('pinjaman')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_bayar,
                    'jenis' => 'Angsuran',
                    'kategori' => 'Pembayaran Angsuran',
                    'keterangan' => 'Pembayaran angsuran ke-' . $item->angsuran_ke . ($item->denda > 0 ? ' (termasuk denda Rp ' . number_format($item->denda, 0, ',', '.') . ')' : ''),
                    'debit' => 0,
                    'kredit' => $item->nominal_dibayar + $item->denda,
                    'saldo' => 0,
                ];
            });

        // Gabungkan semua transaksi dan urutkan berdasarkan tanggal
        $transaksi = collect()
            ->concat($simpanan)
            ->concat($pinjaman)
            ->concat($angsuran)
            ->sortByDesc('tanggal')
            ->values();

        // Hitung statistik
        $totalSimpanan = $simpanan->sum('debit');
        $totalPenarikan = 0; // Jika ada fitur penarikan nanti
        $totalPinjaman = $pinjaman->sum('debit');
        $totalAngsuran = $angsuran->sum('kredit');

        return view('riwayat-transaksi.index', compact(
            'anggota',
            'transaksi',
            'totalSimpanan',
            'totalPenarikan',
            'totalPinjaman',
            'totalAngsuran'
        ));
    }
}
