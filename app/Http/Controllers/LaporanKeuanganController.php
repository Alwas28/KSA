<?php

namespace App\Http\Controllers;

use App\Models\TransaksiUmum;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        return view('laporan-keuangan.index');
    }

    public function labaRugi(Request $request)
    {
        // Filter periode
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // PENDAPATAN
        // Pendapatan dari transaksi_umum (debit selain simpanan & angsuran)
        $pendapatanLain = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'NOT LIKE', '%Simpanan%')
            ->where('kategori', 'NOT LIKE', '%Pembayaran Angsuran%')
            ->sum('nominal');

        $totalPendapatan = $pendapatanLain;

        // BEBAN/BIAYA
        // Semua pengeluaran (kredit) dari transaksi_umum kecuali pencairan pinjaman
        $biayaOperasional = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'NOT LIKE', '%Pencairan Pinjaman%')
            ->sum('nominal');

        // Detail biaya per kategori
        $detailBiaya = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'NOT LIKE', '%Pencairan Pinjaman%')
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();

        $totalBeban = $biayaOperasional;

        // LABA/RUGI BERSIH
        $labaBersih = $totalPendapatan - $totalBeban;

        return view('laporan-keuangan.laba-rugi', compact(
            'startDate',
            'endDate',
            'pendapatanLain',
            'totalPendapatan',
            'biayaOperasional',
            'detailBiaya',
            'totalBeban',
            'labaBersih'
        ));
    }

    public function neraca(Request $request)
    {
        // Tanggal neraca (default: hari ini)
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));

        // AKTIVA
        // 1. Kas - Saldo dari transaksi_umum sampai tanggal neraca
        $kas = TransaksiUmum::where('tanggal', '<=', $tanggal)
            ->selectRaw('
                SUM(CASE WHEN tipe = "debit" THEN nominal ELSE 0 END) as total_debit,
                SUM(CASE WHEN tipe = "kredit" THEN nominal ELSE 0 END) as total_kredit
            ')
            ->first();

        $saldoKas = ($kas->total_debit ?? 0) - ($kas->total_kredit ?? 0);

        // 2. Piutang - Nominal sisa angsuran yang belum dibayar
        $pinjamanAktif = Pinjaman::where('status', 'dicairkan')->get();
        $piutang = $pinjamanAktif->sum(function($pinjaman) {
            return $pinjaman->nominal_sisa_angsuran;
        });

        $totalAktiva = $saldoKas + $piutang;

        // PASIVA
        // 1. Simpanan Anggota per Jenis
        $simpananPerJenis = Simpanan::select('id_jenis_simpanan', DB::raw('SUM(nominal) as total'))
            ->where('created_at', '<=', $tanggal)
            ->groupBy('id_jenis_simpanan')
            ->get();

        $jenisSimpanan = JenisSimpanan::all()->keyBy('id_jenis_simpanan');

        $totalSimpanan = $simpananPerJenis->sum('total');

        // 2. Modal Koperasi (Laba ditahan / SHU tahun berjalan)
        // Hitung laba rugi dari awal tahun sampai tanggal neraca
        $startOfYear = Carbon::parse($tanggal)->startOfYear()->format('Y-m-d');

        $pendapatanLain = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startOfYear, $tanggal])
            ->where('kategori', 'NOT LIKE', '%Simpanan%')
            ->where('kategori', 'NOT LIKE', '%Pembayaran Angsuran%')
            ->sum('nominal');

        $biaya = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startOfYear, $tanggal])
            ->where('kategori', 'NOT LIKE', '%Pencairan Pinjaman%')
            ->sum('nominal');

        $shuTahunBerjalan = $pendapatanLain - $biaya;

        $totalPasiva = $totalSimpanan + $shuTahunBerjalan;

        return view('laporan-keuangan.neraca', compact(
            'tanggal',
            'saldoKas',
            'piutang',
            'totalAktiva',
            'simpananPerJenis',
            'jenisSimpanan',
            'totalSimpanan',
            'shuTahunBerjalan',
            'totalPasiva'
        ));
    }

    public function arusKas(Request $request)
    {
        // Filter periode
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Saldo Kas Awal
        $kasAwal = TransaksiUmum::where('tanggal', '<', $startDate)
            ->selectRaw('
                SUM(CASE WHEN tipe = "debit" THEN nominal ELSE 0 END) as total_debit,
                SUM(CASE WHEN tipe = "kredit" THEN nominal ELSE 0 END) as total_kredit
            ')
            ->first();

        $saldoKasAwal = ($kasAwal->total_debit ?? 0) - ($kasAwal->total_kredit ?? 0);

        // ARUS KAS OPERASIONAL
        // Penerimaan
        $penerimaanSimpanan = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'LIKE', '%Simpanan%')
            ->sum('nominal');

        $penerimaanAngsuran = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'LIKE', '%Pembayaran Angsuran%')
            ->sum('nominal');

        $penerimaanLain = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'NOT LIKE', '%Simpanan%')
            ->where('kategori', 'NOT LIKE', '%Pembayaran Angsuran%')
            ->sum('nominal');

        // Pengeluaran
        $pengeluaranPinjaman = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'LIKE', '%Pencairan Pinjaman%')
            ->sum('nominal');

        $pengeluaranOperasional = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('kategori', 'NOT LIKE', '%Pencairan Pinjaman%')
            ->sum('nominal');

        $totalPenerimaan = $penerimaanSimpanan + $penerimaanAngsuran + $penerimaanLain;
        $totalPengeluaran = $pengeluaranPinjaman + $pengeluaranOperasional;
        $arusKasBersih = $totalPenerimaan - $totalPengeluaran;

        // Saldo Kas Akhir
        $saldoKasAkhir = $saldoKasAwal + $arusKasBersih;

        // Detail arus kas per kategori
        $detailPenerimaan = TransaksiUmum::where('tipe', 'debit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();

        $detailPengeluaran = TransaksiUmum::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->get();

        return view('laporan-keuangan.arus-kas', compact(
            'startDate',
            'endDate',
            'saldoKasAwal',
            'penerimaanSimpanan',
            'penerimaanAngsuran',
            'penerimaanLain',
            'totalPenerimaan',
            'pengeluaranPinjaman',
            'pengeluaranOperasional',
            'totalPengeluaran',
            'arusKasBersih',
            'saldoKasAkhir',
            'detailPenerimaan',
            'detailPengeluaran'
        ));
    }

    public function simpanan(Request $request)
    {
        // Filter tanggal
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));

        // Simpanan per anggota per jenis
        $simpananData = Simpanan::with(['anggota', 'jenisSimpanan'])
            ->where('created_at', '<=', $tanggal)
            ->get()
            ->groupBy('id_anggota');

        // Total per jenis simpanan
        $totalPerJenis = Simpanan::select('id_jenis_simpanan', DB::raw('SUM(nominal) as total'))
            ->where('created_at', '<=', $tanggal)
            ->groupBy('id_jenis_simpanan')
            ->get();

        $jenisSimpanan = JenisSimpanan::all()->keyBy('id_jenis_simpanan');

        // Grand total
        $grandTotal = $totalPerJenis->sum('total');

        return view('laporan-keuangan.simpanan', compact(
            'tanggal',
            'simpananData',
            'totalPerJenis',
            'jenisSimpanan',
            'grandTotal'
        ));
    }

    public function pinjaman(Request $request)
    {
        // Filter periode
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Rekap Pinjaman per Status
        $rekapPinjaman = Pinjaman::select('status', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(pokok_pinjaman) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Rekap Angsuran
        $totalAngsuran = Angsuran::whereHas('pinjaman', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        $angsuranDibayar = Angsuran::where('status', 'dibayar')
            ->whereHas('pinjaman', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

        $angsuranBelumBayar = Angsuran::where('status', 'belum_bayar')
            ->whereHas('pinjaman', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

        $totalNominalDibayar = Angsuran::where('status', 'dibayar')
            ->whereHas('pinjaman', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum(DB::raw('nominal_dibayar'));

        $totalDenda = Angsuran::where('status', 'dibayar')
            ->whereHas('pinjaman', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('denda');

        // Detail Pinjaman
        $detailPinjaman = Pinjaman::with(['anggota'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laporan-keuangan.pinjaman', compact(
            'startDate',
            'endDate',
            'rekapPinjaman',
            'totalAngsuran',
            'angsuranDibayar',
            'angsuranBelumBayar',
            'totalNominalDibayar',
            'totalDenda',
            'detailPinjaman'
        ));
    }
}
