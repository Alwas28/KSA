<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\StatusAnggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with(['user', 'statusAnggota'])->get();
        $statusAnggota = StatusAnggota::all();
        return view('anggota.index', compact('anggota', 'statusAnggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_anggota' => 'required|string|max:255|unique:anggota,no_anggota',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:anggota,email',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:P,L',
            'pekerjaan' => 'nullable|string|max:255',
            'aktif' => 'required|in:Y,N',
            'id_status_anggota' => 'nullable|exists:status_anggota,id_status_anggota',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function show(Anggota $anggotum)
    {
        $anggotum->load(['user', 'statusAnggota']);
        return response()->json($anggotum);
    }

    public function update(Request $request, Anggota $anggotum)
    {
        $validated = $request->validate([
            'no_anggota' => 'required|string|max:255|unique:anggota,no_anggota,' . $anggotum->id_anggota . ',id_anggota',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:anggota,email,' . $anggotum->id_anggota . ',id_anggota',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:P,L',
            'pekerjaan' => 'nullable|string|max:255',
            'aktif' => 'required|in:Y,N',
            'id_status_anggota' => 'nullable|exists:status_anggota,id_status_anggota',
        ]);

        $anggotum->update($validated);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diperbarui!');
    }

    public function destroy(Anggota $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }
}

