<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\TransaksiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersetujuanPinjamanController extends Controller
{
    public function index()
    {
        // Ambil pinjaman yang sudah disetujui tapi belum dicairkan
        $pinjamanBelumCair = Pinjaman::with('anggota')
            ->where('status', 'disetujui')
            ->whereNull('tanggal_pencairan')
            ->orderBy('tanggal_acc', 'desc')
            ->get();

        // Ambil pinjaman yang sudah dicairkan
        $pinjamanSudahCair = Pinjaman::with('anggota', 'angsuran')
            ->where('status', 'disetujui')
            ->whereNotNull('tanggal_pencairan')
            ->orderBy('tanggal_pencairan', 'desc')
            ->get();

        // Ambil pinjaman menunggu persetujuan
        $pinjamanMenunggu = Pinjaman::with('anggota')
            ->where('status', 'diajukan')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('persetujuan-pinjaman.index', compact('pinjamanBelumCair', 'pinjamanSudahCair', 'pinjamanMenunggu'));
    }

    public function cairkan(Request $request, $id_pinjaman)
    {
        $request->validate([
            'tanggal_pencairan' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $pinjaman = Pinjaman::findOrFail($id_pinjaman);

            // Cek apakah pinjaman sudah disetujui
            if ($pinjaman->status !== 'disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman belum disetujui. Setujui pinjaman terlebih dahulu.'
                ], 400);
            }

            // Cek apakah sudah dicairkan
            if ($pinjaman->tanggal_pencairan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman ini sudah dicairkan sebelumnya pada tanggal ' . $pinjaman->tanggal_pencairan->format('d/m/Y')
                ], 400);
            }

            // Update tanggal pencairan
            $pinjaman->update([
                'tanggal_pencairan' => $request->tanggal_pencairan,
            ]);

            // Generate jadwal angsuran
            $generated = $pinjaman->generateJadwalAngsuran();

            // Catat transaksi pencairan sebagai kredit (uang keluar)
            TransaksiUmum::create([
                'tanggal' => $request->tanggal_pencairan,
                'tipe' => 'kredit',
                'kategori' => 'Pencairan Pinjaman',
                'nominal' => $pinjaman->pokok_pinjaman,
                'keterangan' => 'Pencairan Pinjaman an. ' . $pinjaman->anggota->nama,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            if ($generated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat sebanyak ' . $pinjaman->lama_angsuran . ' bulan.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman dicairkan tapi gagal generate jadwal angsuran.'
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
