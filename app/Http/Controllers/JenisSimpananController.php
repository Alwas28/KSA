<?php

namespace App\Http\Controllers;

use App\Models\JenisSimpanan;
use Illuminate\Http\Request;

class JenisSimpananController extends Controller
{
    public function index()
    {
        $jenisSimpanan = JenisSimpanan::all();
        return view('manajemen-simpanan.jenis-simpanan', compact('jenisSimpanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_simpanan,nama_jenis',
            'deskripsi' => 'nullable|string',
        ]);

        JenisSimpanan::create($validated);

        return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil ditambahkan!');
    }

    public function show(JenisSimpanan $jenisSimpanan)
    {
        return response()->json($jenisSimpanan);
    }

    public function update(Request $request, JenisSimpanan $jenisSimpanan)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_simpanan,nama_jenis,' . $jenisSimpanan->id_jenis_simpanan . ',id_jenis_simpanan',
            'deskripsi' => 'nullable|string',
        ]);

        $jenisSimpanan->update($validated);

        return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil diperbarui!');
    }

    public function destroy(JenisSimpanan $jenisSimpanan)
    {
        $jenisSimpanan->delete();
        return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil dihapus!');
    }
}
