<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;

class SimpananSayaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data anggota berdasarkan email user yang login
        $anggota = Anggota::where('email', $user->email)->firstOrFail();

        // Ambil semua simpanan milik anggota yang login
        $simpanan = Simpanan::with(['jenisSimpanan'])
            ->where('id_anggota', $anggota->id_anggota)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total simpanan per jenis
        $totalPerJenis = Simpanan::where('id_anggota', $anggota->id_anggota)
            ->selectRaw('id_jenis_simpanan, SUM(nominal) as total')
            ->groupBy('id_jenis_simpanan')
            ->with('jenisSimpanan')
            ->get();

        // Hitung total keseluruhan simpanan
        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id_anggota)->sum('nominal');

        return view('simpanan-saya.index', compact('simpanan', 'totalPerJenis', 'totalSimpanan', 'anggota'));
    }
}
