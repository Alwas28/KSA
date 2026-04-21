<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\TransaksiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    public function index()
    {
        $simpanan = Simpanan::with(['anggota', 'jenisSimpanan'])->get();
        $anggota = Anggota::where('aktif', 'Y')->get();
        $jenisSimpanan = JenisSimpanan::all();
        return view('manajemen-simpanan.simpanan', compact('simpanan', 'anggota', 'jenisSimpanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_jenis_simpanan' => 'required|exists:jenis_simpanan,id_jenis_simpanan',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data simpanan
            $simpanan = Simpanan::create($validated);

            // Load relasi untuk mendapatkan nama anggota dan jenis simpanan
            $simpanan->load(['anggota', 'jenisSimpanan']);

            // Catat transaksi simpanan sebagai debit (uang masuk)
            TransaksiUmum::create([
                'tanggal' => now(),
                'tipe' => 'debit',
                'kategori' => 'Simpanan ' . $simpanan->jenisSimpanan->nama_jenis,
                'nominal' => $simpanan->nominal,
                'keterangan' => 'Simpanan ' . $simpanan->jenisSimpanan->nama_jenis . ' an. ' . $simpanan->anggota->nama,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('simpanan.index')->with('error', 'Gagal menambahkan simpanan: ' . $e->getMessage());
        }
    }

    public function show(Simpanan $simpanan)
    {
        $simpanan->load(['anggota', 'jenisSimpanan']);
        return response()->json($simpanan);
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_jenis_simpanan' => 'required|exists:jenis_simpanan,id_jenis_simpanan',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $simpanan->update($validated);

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil diperbarui!');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil dihapus!');
    }
}
