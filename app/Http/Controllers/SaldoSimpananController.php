<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\JenisSimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoSimpananController extends Controller
{
    public function index()
    {
        // Ambil semua anggota dengan total simpanan mereka
        $saldoSimpanan = Anggota::with(['statusAnggota'])
            ->where('aktif', 'Y')
            ->get()
            ->map(function ($anggota) {
                // Hitung total simpanan per jenis untuk anggota ini
                $simpananPerJenis = Simpanan::where('id_anggota', $anggota->id_anggota)
                    ->select('id_jenis_simpanan', DB::raw('SUM(nominal) as total'))
                    ->groupBy('id_jenis_simpanan')
                    ->with('jenisSimpanan')
                    ->get();

                // Hitung total keseluruhan
                $totalSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)->sum('nominal');

                return [
                    'anggota' => $anggota,
                    'simpanan_per_jenis' => $simpananPerJenis,
                    'total_simpanan' => $totalSimpanan,
                    'jumlah_transaksi' => Simpanan::where('id_anggota', $anggota->id_anggota)->count(),
                ];
            })
            ->sortByDesc('total_simpanan');

        // Ambil semua jenis simpanan untuk header tabel
        $jenisSimpanan = JenisSimpanan::all();

        // Hitung statistik global
        $totalSemuaSimpanan = Simpanan::sum('nominal');
        $totalAnggota = Anggota::where('aktif', 'Y')->count();
        $rataRataSimpanan = $totalAnggota > 0 ? $totalSemuaSimpanan / $totalAnggota : 0;

        return view('simpanan-saya.saldo', compact('saldoSimpanan', 'jenisSimpanan', 'totalSemuaSimpanan', 'totalAnggota', 'rataRataSimpanan'));
    }

    public function show(Anggota $anggotum)
    {
        // Detail simpanan per anggota
        $anggota = $anggotum->load(['statusAnggota']);

        $simpanan = Simpanan::with(['jenisSimpanan'])
            ->where('id_anggota', $anggota->id_anggota)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPerJenis = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->selectRaw('id_jenis_simpanan, SUM(nominal) as total')
            ->groupBy('id_jenis_simpanan')
            ->with('jenisSimpanan')
            ->get();

        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)->sum('nominal');

        return view('simpanan-saya.detail-saldo', compact('anggota', 'simpanan', 'totalPerJenis', 'totalSimpanan'));
    }
}
