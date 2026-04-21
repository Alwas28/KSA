<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\StatusAnggota;
use Illuminate\Http\Request;

class ProfilAnggotaController extends Controller
{
    // Untuk menampilkan profil anggota yang login
    public function index()
    {
        $user = auth()->user();
        $anggota = Anggota::with(['user', 'statusAnggota', 'simpanan.jenisSimpanan', 'pinjaman'])
            ->where('email', $user->email)
            ->firstOrFail();

        return view('profil.index', compact('anggota'));
    }

    // Untuk menampilkan detail anggota (admin view)
    public function show(Anggota $anggotum)
    {
        $anggotum->load(['user', 'statusAnggota', 'simpanan.jenisSimpanan', 'pinjaman']);
        return view('profil.index', ['anggota' => $anggotum]);
    }

    // Untuk menampilkan form edit profil
    public function edit()
    {
        $user = auth()->user();
        $anggota = Anggota::with(['user', 'statusAnggota'])
            ->where('email', $user->email)
            ->firstOrFail();

        $statusAnggota = StatusAnggota::all();

        return view('profil.edit', compact('anggota', 'statusAnggota'));
    }

    // Untuk update profil anggota
    public function update(Request $request)
    {
        $user = auth()->user();
        $anggota = Anggota::where('email', $user->email)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:P,L',
            'pekerjaan' => 'nullable|string|max:255',
        ]);

        $anggota->update($validated);

        return redirect()->route('profil-anggota.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
