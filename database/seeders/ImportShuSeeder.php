<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportShuSeeder extends Seeder
{
    private const TAHUN       = 2025;
    private const FILE_PATH   = 'shu.xlsx';
    private const SHEET_INDEX = 0;

    public function run(): void
    {
        $file = base_path(self::FILE_PATH);

        if (!file_exists($file)) {
            $this->command->error("File tidak ditemukan: {$file}");
            return;
        }

        $spreadsheet = IOFactory::load($file);
        $sheet       = $spreadsheet->getSheet(self::SHEET_INDEX);
        $rows        = $sheet->toArray();

        // Skip header row
        array_shift($rows);

        // Build lookup: no_anggota → id_anggota
        $anggotaMap = DB::table('anggota')
            ->pluck('id_anggota', 'no_anggota')
            ->toArray();

        // Get or create SHU header untuk tahun ini
        $shu = DB::table('shu')->where('tahun', self::TAHUN)->first();
        if ($shu) {
            $idShu = $shu->id_shu;
            $this->command->line('SHU tahun ' . self::TAHUN . ' sudah ada (id=' . $idShu . '), akan dipakai.');
        } else {
            $idShu = DB::table('shu')->insertGetId([
                'tahun'             => self::TAHUN,
                'total_shu'         => 0,
                'alokasi_anggota'   => 0,
                'alokasi_cadangan'  => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $this->command->line('SHU header tahun ' . self::TAHUN . ' dibuat (id=' . $idShu . ').');
        }

        // Hapus detail lama untuk tahun ini agar tidak duplikat
        $deleted = DB::table('shu_detail')->where('id_shu', $idShu)->delete();
        if ($deleted > 0) {
            $this->command->line("Hapus {$deleted} detail lama untuk tahun " . self::TAHUN . ".");
        }

        $inserted   = 0;
        $skipped    = 0;
        $zeroCount  = 0;
        $totalJumlah = 0;

        foreach ($rows as $row) {
            $noUrut = trim($row[0] ?? '');
            $jumlahRaw = trim($row[2] ?? '0');

            if ($noUrut === '' || $noUrut === null) {
                continue;
            }

            // Cari id_anggota
            $idAnggota = $anggotaMap[$noUrut] ?? null;
            if (!$idAnggota) {
                $this->command->warn("  [SKIP] No anggota tidak ditemukan: {$noUrut}");
                $skipped++;
                continue;
            }

            // Parse jumlah: "254,000" → 254000
            $jumlah = (int) str_replace([',', '.', ' '], '', $jumlahRaw);

            DB::table('shu_detail')->insert([
                'id_shu'      => $idShu,
                'id_anggota'  => $idAnggota,
                'jumlah'      => $jumlah,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($jumlah === 0) $zeroCount++;
            $totalJumlah += $jumlah;
            $inserted++;
        }

        // Update total_shu di header
        DB::table('shu')->where('id_shu', $idShu)->update([
            'total_shu'        => $totalJumlah,
            'alokasi_anggota'  => $totalJumlah,
            'updated_at'       => now(),
        ]);

        $this->command->info("✓ Import selesai:");
        $this->command->info("  - Tahun        : " . self::TAHUN);
        $this->command->info("  - Berhasil     : {$inserted} anggota");
        $this->command->info("  - Dilewati     : {$skipped} (no anggota tidak cocok)");
        $this->command->info("  - SHU = 0      : {$zeroCount} anggota");
        $this->command->info("  - Total SHU    : Rp " . number_format($totalJumlah, 0, ',', '.'));
    }
}
