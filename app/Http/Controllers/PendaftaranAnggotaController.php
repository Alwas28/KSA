<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\StatusAnggota;
use Illuminate\Http\Request;

class PendaftaranAnggotaController extends Controller
{
    public function index()
    {
        // Ambil anggota yang belum diverifikasi (id_status_anggota = null dan aktif = N)
        $pendaftaran = Anggota::with(['user', 'statusAnggota'])
            ->whereNull('id_status_anggota')
            ->where('aktif', 'N')
            ->get();

        $statusAnggota = StatusAnggota::all();

        return view('keanggotaan.pendaftaran-anggota', compact('pendaftaran', 'statusAnggota'));
    }

    public function show(Anggota $anggotum)
    {
        $anggotum->load(['user', 'statusAnggota']);
        return response()->json($anggotum);
    }

    public function approve(Request $request, Anggota $anggotum)
    {
        $validated = $request->validate([
            'id_status_anggota' => 'required|exists:status_anggota,id_status_anggota',
        ]);

        $anggotum->update([
            'id_status_anggota' => $validated['id_status_anggota'],
            'aktif' => 'Y',
        ]);

        return redirect()->route('pendaftaran-anggota.index')->with('success', 'Anggota berhasil diaktifkan!');
    }

    public function destroy(Anggota $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('pendaftaran-anggota.index')->with('success', 'Pendaftaran anggota berhasil ditolak dan dihapus!');
    }
}
