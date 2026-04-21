<?php

namespace App\Http\Controllers;

use App\Models\TransaksiUmum;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuKasController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Ambil semua transaksi dalam periode
        $transaksi = TransaksiUmum::with('creator')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal (semua transaksi sebelum start_date)
        $saldoAwal = TransaksiUmum::where('tanggal', '<', $startDate)
            ->selectRaw('
                SUM(CASE WHEN tipe = "debit" THEN nominal ELSE 0 END) as total_debit,
                SUM(CASE WHEN tipe = "kredit" THEN nominal ELSE 0 END) as total_kredit
            ')
            ->first();

        $saldoAwalValue = ($saldoAwal->total_debit ?? 0) - ($saldoAwal->total_kredit ?? 0);

        // Hitung saldo berjalan
        $saldoBerjalan = $saldoAwalValue;
        $transaksiWithSaldo = $transaksi->map(function ($item) use (&$saldoBerjalan) {
            if ($item->tipe === 'debit') {
                $saldoBerjalan += $item->nominal;
            } else {
                $saldoBerjalan -= $item->nominal;
            }
            $item->saldo = $saldoBerjalan;
            return $item;
        });

        // Statistik
        $totalDebit = $transaksi->where('tipe', 'debit')->sum('nominal');
        $totalKredit = $transaksi->where('tipe', 'kredit')->sum('nominal');
        $saldoAkhir = $saldoBerjalan;

        // Transaksi per kategori
        $transaksiPerKategori = TransaksiUmum::whereBetween('tanggal', [$startDate, $endDate])
            ->select('kategori', 'tipe', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori', 'tipe')
            ->get()
            ->groupBy('kategori');

        return view('buku-kas.index', compact(
            'transaksiWithSaldo',
            'saldoAwalValue',
            'totalDebit',
            'totalKredit',
            'saldoAkhir',
            'startDate',
            'endDate',
            'transaksiPerKategori'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:debit,kredit',
            'kategori' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string',
        ]);

        try {
            $validated['created_by'] = Auth::id();

            TransaksiUmum::create($validated);

            return redirect()->route('buku-kas.index')
                ->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('buku-kas.index')
                ->with('error', 'Gagal menambahkan transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = TransaksiUmum::with('creator')->findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:debit,kredit',
            'kategori' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string',
        ]);

        try {
            $transaksi = TransaksiUmum::findOrFail($id);
            $transaksi->update($validated);

            return redirect()->route('buku-kas.index')
                ->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('buku-kas.index')
                ->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $transaksi = TransaksiUmum::findOrFail($id);
            $transaksi->delete();

            return redirect()->route('buku-kas.index')
                ->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('buku-kas.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
