<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\JenisSimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah user memiliki role 'Anggota'
        $isAnggota = $user->roles()->where('name', 'Anggota')->exists();

        // Jika bukan anggota, tampilkan dashboard default
        if (!$isAnggota) {
            return $this->dashboardDefault();
        }

        // Cari anggota berdasarkan email user yang login
        $anggota = Anggota::where('email', $user->email)->first();

        if (!$anggota) {
            return $this->dashboardDefault();
        }

        // Statistik Simpanan
        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)->sum('nominal');

        $simpananPerJenis = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->select('id_jenis_simpanan', \DB::raw('SUM(nominal) as total'))
            ->groupBy('id_jenis_simpanan')
            ->with('jenisSimpanan')
            ->get();

        // Statistik Pinjaman
        $totalPinjaman = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->where('status', 'disetujui')
            ->sum('pokok_pinjaman');

        $totalPinjamanAktif = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status_angsuran', ['aktif'])
            ->count();

        // Statistik Angsuran
        $sisaAngsuran = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })->where('status', 'belum_bayar')->count();

        $angsuranTelat = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })->where(function ($query) {
            $query->where('status', 'telat')
                ->orWhere(function ($q) {
                    $q->where('status', 'belum_bayar')
                        ->where('tanggal_jatuh_tempo', '<', Carbon::now());
                });
        })->count();

        // Pinjaman Terbaru
        $pinjamanTerbaru = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->limit(5)
            ->get();

        // Angsuran Terdekat (yang belum dibayar)
        $angsuranTerdekat = Angsuran::whereHas('pinjaman', function ($query) use ($anggota) {
            $query->where('id_anggota', $anggota->id_anggota);
        })
            ->where('status', 'belum_bayar')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->with('pinjaman.anggota')
            ->limit(5)
            ->get();

        // Riwayat Simpanan Terbaru
        $riwayatSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->with('jenisSimpanan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.anggota', compact(
            'anggota',
            'totalSimpanan',
            'simpananPerJenis',
            'totalPinjaman',
            'totalPinjamanAktif',
            'sisaAngsuran',
            'angsuranTelat',
            'pinjamanTerbaru',
            'angsuranTerdekat',
            'riwayatSimpanan'
        ));
    }

    private function dashboardDefault()
    {
        $user = Auth::user();

        // Statistik untuk Admin/Pengurus
        $totalAnggota = Anggota::where('aktif', 'Y')->count();
        $totalSimpanan = Simpanan::sum('nominal');
        $totalPinjaman = Pinjaman::where('status', 'disetujui')->sum('pokok_pinjaman');
        $pinjamanMenunggu = Pinjaman::where('status', 'diajukan')->count();

        return view('dashboard', compact(
            'totalAnggota',
            'totalSimpanan',
            'totalPinjaman',
            'pinjamanMenunggu'
        ));
    }
}
