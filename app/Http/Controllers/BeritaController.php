<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with('penulis')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $berita = $query->paginate(15)->withQueryString();
        return view('manajemen-berita.index', compact('berita'));
    }

    public function create()
    {
        return view('manajemen-berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|in:Berita,Pengumuman,Artikel',
            'ringkasan'  => 'nullable|string|max:500',
            'konten'     => 'required|string',
            'gambar_url' => 'nullable|string|max:500',
            'status'     => 'required|in:draft,published',
        ]);

        $validated['slug']       = Berita::generateSlug($request->judul);
        $validated['id_penulis'] = auth()->id();

        Berita::create($validated);

        return redirect()->route('manajemen-berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Berita $berita)
    {
        return view('manajemen-berita.show', compact('berita'));
    }

    public function edit(Berita $berita)
    {
        return view('manajemen-berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|in:Berita,Pengumuman,Artikel',
            'ringkasan'  => 'nullable|string|max:500',
            'konten'     => 'required|string',
            'gambar_url' => 'nullable|string|max:500',
            'status'     => 'required|in:draft,published',
        ]);

        if ($berita->judul !== $validated['judul']) {
            $validated['slug'] = Berita::generateSlug($validated['judul']);
        }

        $berita->update($validated);

        return redirect()->route('manajemen-berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('manajemen-berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
