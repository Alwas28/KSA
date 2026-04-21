<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUsaha;
use Illuminate\Http\Request;

class KegiatanUsahaController extends Controller
{
    public function index()
    {
        $kegiatanUsaha = KegiatanUsaha::withCount('transaksi')->latest()->get();
        return view('kegiatan-usaha.index', compact('kegiatanUsaha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        KegiatanUsaha::create($request->all());

        return redirect()->route('kegiatan-usaha.index')->with('success', 'Kegiatan usaha berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kegiatan = KegiatanUsaha::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->route('kegiatan-usaha.index')->with('success', 'Kegiatan usaha berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kegiatan = KegiatanUsaha::findOrFail($id);
        $kegiatan->delete();

        return redirect()->route('kegiatan-usaha.index')->with('success', 'Kegiatan usaha berhasil dihapus!');
    }

    public function show($id)
    {
        $kegiatan = KegiatanUsaha::findOrFail($id);
        return response()->json($kegiatan);
    }
}
