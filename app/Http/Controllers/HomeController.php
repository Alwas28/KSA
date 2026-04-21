<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $berita = Berita::where('status', 'published')->latest()->take(3)->get();
        } catch (\Exception $e) {
            $berita = collect();
        }
        return view('home', compact('berita'));
    }
}
