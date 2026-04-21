<?php

namespace App\Http\Controllers;

use App\Models\StatusAnggota;
use Illuminate\Http\Request;

class StatusAnggotaController extends Controller
{
    public function index()
    {
        $statusAnggota = StatusAnggota::all();
        return view('master-data.status-anggota', compact('statusAnggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:100|unique:status_anggota,nama_status',
            'deskripsi' => 'nullable|string',
        ]);

        StatusAnggota::create($validated);

        return redirect()->route('status-anggota.index')->with('success', 'Status anggota berhasil ditambahkan!');
    }

    public function show(StatusAnggota $statusAnggotum)
    {
        return response()->json($statusAnggotum);
    }

    public function update(Request $request, StatusAnggota $statusAnggotum)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:100|unique:status_anggota,nama_status,' . $statusAnggotum->id_status_anggota . ',id_status_anggota',
            'deskripsi' => 'nullable|string',
        ]);

        $statusAnggotum->update($validated);

        return redirect()->route('status-anggota.index')->with('success', 'Status anggota berhasil diperbarui!');
    }

    public function destroy(StatusAnggota $statusAnggotum)
    {
        $statusAnggotum->delete();
        return redirect()->route('status-anggota.index')->with('success', 'Status anggota berhasil dihapus!');
    }
}
