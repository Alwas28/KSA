<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\JenisSimpanan;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\Shu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Anggota')) {
            return $this->dashboardAnggota($user);
        }

        if ($user->hasRole('Ketua')) {
            return $this->dashboardKetua();
        }

        return $this->dashboardAdmin();
    }

    private function dashboardAdmin()
    {
        $totalAnggota   = Anggota::where('aktif', 'Y')->count();
        $totalSimpanan  = Simpanan::sum('nominal');
        $pinjamanMenunggu = Pinjaman::where('status', 'diajukan')->count();
        $pinjamanBerjalan = Pinjaman::where('status_angsuran', 'aktif')->count();

        $simpananPerJenis = JenisSimpanan::withSum('simpanan as total', 'nominal')
            ->orderByDesc('total')
            ->get();

        $shuTerbaru = Shu::orderBy('tahun', 'desc')->first();

        $pinjamanTerbaru = Pinjaman::with('anggota')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->limit(5)
            ->get();

        $beritaTerbaru = [];
        try {
            $beritaTerbaru = Berita::where('status', 'published')
                ->orderBy('tanggal', 'desc')
                ->limit(3)
                ->get(['judul', 'slug', 'kategori', 'tanggal']);
        } catch (\Exception $e) {
            $beritaTerbaru = collect();
        }

        return view('dashboard', compact(
            'totalAnggota',
            'totalSimpanan',
            'pinjamanMenunggu',
            'pinjamanBerjalan',
            'simpananPerJenis',
            'shuTerbaru',
            'pinjamanTerbaru',
            'beritaTerbaru'
        ));
    }

    private function dashboardKetua()
    {
        $totalAnggota     = Anggota::where('aktif', 'Y')->count();
        $totalSimpanan    = Simpanan::sum('nominal');
        $pinjamanBerjalan = Pinjaman::where('status_angsuran', 'aktif')->count();
        $pinjamanMenunggu = Pinjaman::where('status', 'diajukan')->count();

        $simpananPerJenis = JenisSimpanan::withSum('simpanan as total', 'nominal')
            ->orderByDesc('total')
            ->get();

        $shuTerbaru = Shu::orderBy('tahun', 'desc')->first();

        $beritaTerbaru = [];
        try {
            $beritaTerbaru = Berita::where('status', 'published')
                ->orderBy('tanggal', 'desc')
                ->limit(3)
                ->get(['judul', 'slug', 'kategori', 'tanggal']);
        } catch (\Exception $e) {
            $beritaTerbaru = collect();
        }

        return view('dashboard.ketua', compact(
            'totalAnggota',
            'totalSimpanan',
            'pinjamanBerjalan',
            'pinjamanMenunggu',
            'simpananPerJenis',
            'shuTerbaru',
            'beritaTerbaru'
        ));
    }

    private function dashboardAnggota($user)
    {
        $anggota = Anggota::with(['statusAnggota'])
            ->where('email', $user->email)
            ->first();

        if (!$anggota) {
            return view('dashboard.anggota', [
                'anggota'          => null,
                'totalSimpanan'    => 0,
                'simpananPerJenis' => collect(),
                'totalPinjaman'    => 0,
                'totalPinjamanAktif' => 0,
                'sisaAngsuran'     => 0,
                'angsuranTelat'    => 0,
                'pinjamanTerbaru'  => collect(),
                'angsuranTerdekat' => collect(),
                'riwayatSimpanan'  => collect(),
                'shuAnggota'       => null,
            ]);
        }

        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)->sum('nominal');

        $simpananPerJenis = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->select('id_jenis_simpanan', DB::raw('SUM(nominal) as total'))
            ->groupBy('id_jenis_simpanan')
            ->with('jenisSimpanan')
            ->get();

        $totalPinjaman = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->where('status', 'disetujui')
            ->sum('pokok_pinjaman');

        $totalPinjamanAktif = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->where('status_angsuran', 'aktif')
            ->count();

        $pinjamanIds = Pinjaman::where('id_anggota', $anggota->id_anggota)->pluck('id_pinjaman');

        $sisaAngsuran = \App\Models\Angsuran::whereIn('id_pinjaman', $pinjamanIds)
            ->where('status', 'belum_bayar')
            ->count();

        $angsuranTelat = \App\Models\Angsuran::whereIn('id_pinjaman', $pinjamanIds)
            ->where(function ($q) {
                $q->where('status', 'telat')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'belum_bayar')
                         ->where('tanggal_jatuh_tempo', '<', now());
                  });
            })->count();

        $pinjamanTerbaru = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->limit(5)
            ->get();

        $angsuranTerdekat = \App\Models\Angsuran::whereIn('id_pinjaman', $pinjamanIds)
            ->where('status', 'belum_bayar')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        $riwayatSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->with('jenisSimpanan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $shuAnggota = \App\Models\ShuDetail::with('shu')
            ->where('id_anggota', $anggota->id_anggota)
            ->orderByDesc(
                \App\Models\Shu::select('tahun')
                    ->whereColumn('shu.id_shu', 'shu_detail.id_shu')
                    ->limit(1)
            )
            ->first();

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
            'riwayatSimpanan',
            'shuAnggota'
        ));
    }
}
