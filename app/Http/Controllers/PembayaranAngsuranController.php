<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\TransaksiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranAngsuranController extends Controller
{
    public function index()
    {
        // Ambil semua pinjaman yang sudah dicairkan dengan angsuran yang masih aktif
        $pinjaman = Pinjaman::with(['anggota', 'angsuran' => function ($query) {
            $query->orderBy('angsuran_ke', 'asc');
        }])
            ->whereIn('status_angsuran', ['aktif', 'macet'])
            ->whereNotNull('tanggal_pencairan')
            ->orderBy('tanggal_pencairan', 'desc')
            ->get();

        // Statistik
        $totalPinjaman = Pinjaman::whereIn('status_angsuran', ['aktif', 'macet'])->count();
        $totalAngsuranBelumBayar = Angsuran::where('status', 'belum_bayar')->count();
        $totalAngsuranTelat = Angsuran::where('status', 'telat')
            ->orWhere(function ($query) {
                $query->where('status', 'belum_bayar')
                    ->where('tanggal_jatuh_tempo', '<', Carbon::now());
            })
            ->count();

        return view('pembayaran-angsuran.index', compact('pinjaman', 'totalPinjaman', 'totalAngsuranBelumBayar', 'totalAngsuranTelat'));
    }

    public function detail($id_pinjaman)
    {
        $pinjaman = Pinjaman::with(['anggota', 'angsuran' => function ($query) {
            $query->orderBy('angsuran_ke', 'asc');
        }])->findOrFail($id_pinjaman);

        return view('pembayaran-angsuran.detail', compact('pinjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_angsuran' => 'required|exists:angsuran,id_angsuran',
            'nominal_dibayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $angsuran = Angsuran::findOrFail($request->id_angsuran);

            // Cek apakah sudah dibayar
            if ($angsuran->status === 'dibayar') {
                return response()->json([
                    'success' => false,
                    'message' => 'Angsuran ini sudah dibayar sebelumnya.'
                ], 400);
            }

            // Update angsuran
            $angsuran->update([
                'tanggal_bayar' => $request->tanggal_bayar,
                'nominal_dibayar' => $request->nominal_dibayar,
                'keterangan' => $request->keterangan,
                'status' => 'dibayar',
                'dibayar_oleh' => Auth::id(),
            ]);

            // Update sisa angsuran pada pinjaman
            $pinjaman = $angsuran->pinjaman;
            $pinjaman->updateSisaAngsuran();

            // Catat transaksi pembayaran angsuran sebagai debit (uang masuk)
            TransaksiUmum::create([
                'tanggal' => $request->tanggal_bayar,
                'tipe' => 'debit',
                'kategori' => 'Pembayaran Angsuran',
                'nominal' => $request->nominal_dibayar,
                'keterangan' => 'Pembayaran angsuran an. ' . $pinjaman->anggota->nama,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran angsuran berhasil dicatat.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cairkan(Request $request, $id_pinjaman)
    {
        $request->validate([
            'tanggal_pencairan' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $pinjaman = Pinjaman::findOrFail($id_pinjaman);

            // Cek apakah sudah dicairkan
            if ($pinjaman->tanggal_pencairan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman ini sudah dicairkan sebelumnya.'
                ], 400);
            }

            // Update tanggal pencairan
            $pinjaman->update([
                'tanggal_pencairan' => $request->tanggal_pencairan,
            ]);

            // Generate jadwal angsuran
            $pinjaman->generateJadwalAngsuran();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
