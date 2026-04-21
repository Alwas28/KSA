<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ShuDetail;

class RiwayatShuController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $anggota = Anggota::where('email', $user->email)->first();

        $riwayat = [];
        if ($anggota) {
            $riwayat = ShuDetail::with('shu')
                ->where('id_anggota', $anggota->id_anggota)
                ->join('shu', 'shu_detail.id_shu', '=', 'shu.id_shu')
                ->select('shu_detail.*')
                ->orderBy('shu.tahun', 'desc')
                ->get();
        }

        return view('riwayat-shu.index', compact('anggota', 'riwayat'));
    }
}
