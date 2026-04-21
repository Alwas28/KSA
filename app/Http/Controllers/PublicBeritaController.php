<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class PublicBeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::where('status', 'published')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $berita       = $query->paginate(9)->withQueryString();
        $kategoriAktif = $request->get('kategori', '');

        return view('public.berita.index', compact('berita', 'kategoriAktif'));
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'published')
            ->with('penulis')
            ->firstOrFail();

        $related = Berita::where('status', 'published')
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.berita.show', compact('berita', 'related'));
    }
}
