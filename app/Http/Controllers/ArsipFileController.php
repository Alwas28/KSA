<?php

namespace App\Http\Controllers;

use App\Models\ArsipFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipFileController extends Controller
{
    public function index()
    {
        $arsipFiles = ArsipFile::with('uploader')->latest()->get();
        return view('arsip-file.index', compact('arsipFiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'keterangan' => 'nullable|string'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('arsip', $fileName, 'public');

            ArsipFile::create([
                'nama_file' => $request->nama_file,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'keterangan' => $request->keterangan,
                'uploaded_by' => auth()->id()
            ]);

            return redirect()->route('arsip-file.index')->with('success', 'File berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file!');
    }

    public function download($id)
    {
        $arsipFile = ArsipFile::findOrFail($id);

        if (Storage::disk('public')->exists($arsipFile->file_path)) {
            return Storage::disk('public')->download($arsipFile->file_path, $arsipFile->nama_file . '.' . $arsipFile->file_type);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }

    public function destroy($id)
    {
        $arsipFile = ArsipFile::findOrFail($id);

        if (Storage::disk('public')->exists($arsipFile->file_path)) {
            Storage::disk('public')->delete($arsipFile->file_path);
        }

        $arsipFile->delete();

        return redirect()->route('arsip-file.index')->with('success', 'File berhasil dihapus!');
    }
}
