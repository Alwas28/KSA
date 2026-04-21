<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamanController extends Controller
{
    public function index()
    {
        $pinjaman = Pinjaman::with(['anggota'])->orderBy('tanggal_pengajuan', 'desc')->get();
        $anggota = Anggota::where('aktif', 'Y')->get();

        return view('pinjaman.index', compact('pinjaman', 'anggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tanggal_pengajuan' => 'required|date',
            'pokok_pinjaman' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|integer|min:1',
            'status' => 'required|in:diajukan,disetujui,dicairkan,ditolak,lunas',
        ]);

        // Set tanggal_acc jika status disetujui
        if ($validated['status'] === 'disetujui') {
            $validated['tanggal_acc'] = now();
        }

        Pinjaman::create($validated);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan!');
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load(['anggota']);
        return response()->json($pinjaman);
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tanggal_pengajuan' => 'required|date',
            'pokok_pinjaman' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|integer|min:1',
            'status' => 'required|in:diajukan,disetujui,dicairkan,ditolak,lunas',
        ]);

        // Set tanggal_acc jika status diubah menjadi disetujui dan sebelumnya belum ada
        if ($validated['status'] === 'disetujui' && !$pinjaman->tanggal_acc) {
            $validated['tanggal_acc'] = now();
        }

        // Reset tanggal_acc jika status diubah menjadi ditolak atau diajukan
        if (in_array($validated['status'], ['ditolak', 'diajukan'])) {
            $validated['tanggal_acc'] = null;
        }

        $pinjaman->update($validated);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil diperbarui!');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus!');
    }

    // Method untuk menyetujui pinjaman
    public function approve(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'lama_angsuran' => 'required|integer|min:1',
        ]);

        $pinjaman->update([
            'status' => 'disetujui',
            'tanggal_acc' => now(),
            'lama_angsuran' => $validated['lama_angsuran'],
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil disetujui dengan lama angsuran ' . $validated['lama_angsuran'] . ' bulan!');
    }

    // Method untuk menolak pinjaman
    public function reject(Pinjaman $pinjaman)
    {
        $pinjaman->update([
            'status' => 'ditolak',
            'tanggal_acc' => null,
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditolak!');
    }

    // Method untuk mencairkan pinjaman
    public function cairkan(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'tanggal_pencairan' => 'required|date',
        ]);

        // Pastikan status pinjaman adalah disetujui
        if ($pinjaman->status !== 'disetujui') {
            return redirect()->route('pinjaman.index')->with('error', 'Hanya pinjaman yang sudah disetujui yang bisa dicairkan!');
        }

        // Update tanggal pencairan dan status
        $pinjaman->update([
            'tanggal_pencairan' => $validated['tanggal_pencairan'],
            'status' => 'dicairkan',
        ]);

        // Generate jadwal angsuran otomatis
        $pinjaman->generateJadwalAngsuran();

        // Catat transaksi kredit di Buku Kas
        \App\Models\TransaksiUmum::create([
            'tanggal' => $validated['tanggal_pencairan'],
            'tipe' => 'kredit',
            'kategori' => 'Pencairan Pinjaman',
            'nominal' => $pinjaman->pokok_pinjaman,
            'keterangan' => 'Pencairan pinjaman an. ' . $pinjaman->anggota->nama . ' (' . $pinjaman->anggota->no_anggota . ')',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat!');
    }
}
