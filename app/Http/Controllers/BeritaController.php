<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with('penulis')->latest('tanggal');

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
            'judul'     => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:berita,slug',
            'kategori'  => 'required|in:Berita,Pengumuman,Artikel',
            'tanggal'   => 'required|date',
            'ringkasan' => 'nullable|string|max:500',
            'konten'    => 'required|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'    => 'required|in:draft,published',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('berita', 'public');
        } else {
            unset($validated['gambar']);
        }

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
            'judul'     => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:berita,slug,' . $berita->id,
            'kategori'  => 'required|in:Berita,Pengumuman,Artikel',
            'tanggal'   => 'required|date',
            'ringkasan' => 'nullable|string|max:500',
            'konten'    => 'required|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'    => 'required|in:draft,published',
        ]);

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $validated['gambar'] = $request->file('gambar')
                ->store('berita', 'public');
        } else {
            unset($validated['gambar']);
        }

        $berita->update($validated);

        return redirect()->route('manajemen-berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }
        $berita->delete();

        return redirect()->route('manajemen-berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096']);
        $path = $request->file('image')->store('berita/konten', 'public');
        return response()->json(['url' => Storage::url($path)]);
    }
}
