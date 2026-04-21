<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Shu;
use App\Models\ShuDetail;
use Illuminate\Http\Request;

class ShuController extends Controller
{
    public function index(Request $request)
    {
        $tahunFilter = $request->input('tahun');

        $query = ShuDetail::with(['anggota', 'shu'])
            ->join('shu', 'shu_detail.id_shu', '=', 'shu.id_shu')
            ->join('anggota', 'shu_detail.id_anggota', '=', 'anggota.id_anggota')
            ->select('shu_detail.*');

        if ($tahunFilter) {
            $query->where('shu.tahun', $tahunFilter);
        }

        $data    = $query->orderBy('shu.tahun', 'desc')->orderBy('anggota.nama')->paginate(20)->withQueryString();
        $tahunList = Shu::orderBy('tahun', 'desc')->pluck('tahun');
        $anggota = Anggota::where('aktif', 'Y')->orderBy('nama')->get(['id_anggota', 'no_anggota', 'nama']);

        return view('shu.index', compact('data', 'tahunList', 'anggota', 'tahunFilter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tahun'      => 'required|integer|min:2000|max:2100',
            'jumlah'     => 'required|integer|min:0',
        ]);

        // Auto-create shu header jika belum ada untuk tahun tersebut
        $shu = Shu::firstOrCreate(
            ['tahun' => $request->tahun],
            ['total_shu' => 0, 'alokasi_anggota' => 0, 'alokasi_cadangan' => 0]
        );

        // Cek duplikasi
        $exists = ShuDetail::where('id_shu', $shu->id_shu)
            ->where('id_anggota', $request->id_anggota)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data SHU anggota ini untuk tahun tersebut sudah ada.');
        }

        ShuDetail::create([
            'id_shu'     => $shu->id_shu,
            'id_anggota' => $request->id_anggota,
            'jumlah'     => $request->jumlah,
        ]);

        return back()->with('success', 'Data SHU berhasil ditambahkan.');
    }

    public function show(ShuDetail $shuDetail)
    {
        return response()->json($shuDetail->load(['anggota', 'shu']));
    }

    public function update(Request $request, ShuDetail $shuDetail)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
        ]);

        $shuDetail->update(['jumlah' => $request->jumlah]);

        return back()->with('success', 'Data SHU berhasil diperbarui.');
    }

    public function destroy(ShuDetail $shuDetail)
    {
        $shuDetail->delete();
        return back()->with('success', 'Data SHU berhasil dihapus.');
    }
}
