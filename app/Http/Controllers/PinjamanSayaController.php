<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class PinjamanSayaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data anggota berdasarkan email user yang login
        $anggota = Anggota::where('email', $user->email)->firstOrFail();

        // Ambil semua pinjaman milik anggota yang login
        $pinjaman = Pinjaman::where('id_anggota', $anggota->id_anggota)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        // Hitung statistik
        $totalPinjaman = $pinjaman->whereIn('status', ['disetujui', 'dicairkan'])->sum('pokok_pinjaman');
        $pinjamanAktif = $pinjaman->whereIn('status', ['diajukan', 'disetujui', 'dicairkan'])->count();
        $pinjamanLunas = $pinjaman->where('status', 'lunas')->count();

        return view('pinjaman-saya.index', compact('pinjaman', 'anggota', 'totalPinjaman', 'pinjamanAktif', 'pinjamanLunas'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $anggota = Anggota::where('email', $user->email)->firstOrFail();

        $validated = $request->validate([
            'pokok_pinjaman' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|integer|min:1',
        ]);

        $validated['id_anggota'] = $anggota->id_anggota;
        $validated['tanggal_pengajuan'] = now();
        $validated['status'] = 'diajukan';

        Pinjaman::create($validated);

        return redirect()->route('pinjaman-saya.index')->with('success', 'Pengajuan pinjaman berhasil dikirim! Mohon menunggu persetujuan admin.');
    }
}
