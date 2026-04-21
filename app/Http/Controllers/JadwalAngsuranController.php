<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalAngsuranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari anggota berdasarkan email user yang login
        $anggota = \App\Models\Anggota::where('email', $user->email)->first();

        // Jika user bukan anggota, return empty
        if (!$anggota) {
            return view('jadwal-angsuran.index', [
                'pinjaman' => collect([]),
                'totalPinjaman' => 0,
                'totalAngsuranBelumBayar' => 0,
                'totalAngsuranSudahBayar' => 0,
                'totalAngsuranTelat' => 0
            ]);
        }

        // Ambil semua pinjaman anggota yang sudah dicairkan
        $pinjaman = Pinjaman::with(['angsuran' => function ($query) {
            $query->orderBy('angsuran_ke', 'asc');
        }])
            ->where('id_anggota', $anggota->id_anggota)
            ->whereNotNull('tanggal_pencairan')
            ->whereIn('status_angsuran', ['aktif', 'selesai', 'macet'])
            ->orderBy('tanggal_pencairan', 'desc')
            ->get();

        // Statistik
        $totalPinjaman = $pinjaman->count();
        $totalAngsuranBelumBayar = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })->where('status', 'belum_bayar')->count();

        $totalAngsuranSudahBayar = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })->where('status', 'dibayar')->count();

        $totalAngsuranTelat = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })->where(function ($query) {
            $query->where('status', 'telat')
                ->orWhere(function ($q) {
                    $q->where('status', 'belum_bayar')
                        ->where('tanggal_jatuh_tempo', '<', Carbon::now());
                });
        })->count();

        return view('jadwal-angsuran.index', compact('pinjaman', 'totalPinjaman', 'totalAngsuranBelumBayar', 'totalAngsuranSudahBayar', 'totalAngsuranTelat'));
    }

    public function detail($id_pinjaman)
    {
        $user = Auth::user();

        // Cari anggota berdasarkan email user yang login
        $anggota = \App\Models\Anggota::where('email', $user->email)->first();

        if (!$anggota) {
            abort(403, 'Anda bukan anggota terdaftar.');
        }

        $pinjaman = Pinjaman::with(['angsuran' => function ($query) {
            $query->orderBy('angsuran_ke', 'asc');
        }])
            ->where('id_anggota', $anggota->id_anggota)
            ->findOrFail($id_pinjaman);

        return view('jadwal-angsuran.detail', compact('pinjaman'));
    }
}
