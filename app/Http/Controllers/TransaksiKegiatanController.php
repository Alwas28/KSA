<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUsaha;
use App\Models\TransaksiKegiatan;
use Illuminate\Http\Request;

class TransaksiKegiatanController extends Controller
{
    public function index()
    {
        $kegiatanAktif = KegiatanUsaha::where('status', 'aktif')->get();
        $transaksi = TransaksiKegiatan::with(['kegiatan', 'creator'])->latest('tanggal_transaksi')->get();

        return view('transaksi-kegiatan.index', compact('kegiatanAktif', 'transaksi'));
    }

    public function detail($id)
    {
        $kegiatan = KegiatanUsaha::findOrFail($id);
        $transaksi = TransaksiKegiatan::where('id_kegiatan', $id)
            ->with('creator')
            ->latest('tanggal_transaksi')
            ->get();

        $totalPemasukan = $transaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksi->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('transaksi-kegiatan.detail', compact('kegiatan', 'transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required|exists:kegiatan_usaha,id_kegiatan',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string'
        ]);

        TransaksiKegiatan::create([
            'id_kegiatan' => $request->id_kegiatan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id()
        ]);

        if ($request->from_detail) {
            return redirect()->route('transaksi-kegiatan.detail', $request->id_kegiatan)->with('success', 'Transaksi berhasil ditambahkan!');
        }

        return redirect()->route('transaksi-kegiatan.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiKegiatan::findOrFail($id);
        $idKegiatan = $transaksi->id_kegiatan;
        $transaksi->delete();

        return redirect()->route('transaksi-kegiatan.detail', $idKegiatan)->with('success', 'Transaksi berhasil dihapus!');
    }
}
