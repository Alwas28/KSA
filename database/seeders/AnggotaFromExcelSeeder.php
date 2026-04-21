<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnggotaFromExcelSeeder extends Seeder
{
    private string $excelPath;

    public function __construct()
    {
        $this->excelPath = base_path('ksa.xlsx');
    }

    public function run(): void
    {
        $spreadsheet = IOFactory::load($this->excelPath);

        $this->importAnggota($spreadsheet);
        $this->importSimpanan($spreadsheet);
    }

    private function importAnggota($spreadsheet): void
    {
        $sheet = $spreadsheet->getSheetByName('ANGGOTA');
        $rows  = $sheet->toArray(null, true, true, false);

        $statusAktifId = DB::table('status_anggota')
            ->where('nama_status', 'Aktif')
            ->value('id_status_anggota') ?? 1;

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $row) {
            // Validasi baris data: kolom 0 = nomor (angka > 0), kolom 3 = KSA_xxx
            if (
                !isset($row[0], $row[3]) ||
                !is_numeric($row[0]) || $row[0] <= 0 ||
                strpos((string) $row[3], 'KSA_') !== 0
            ) {
                continue;
            }

            $noAnggota = trim($row[3]);
            $nama      = trim($row[4] ?? '');
            $jk        = trim($row[1] ?? '');
            $pekerjaan = trim($row[2] ?? '');

            // Generate email dari no_anggota: KSA_001 -> ksa001@umkendari.ac.id
            $emailPrefix = strtolower(str_replace('_', '', $noAnggota));
            $email       = $emailPrefix . '@umkendari.ac.id';

            // Pastikan jk valid
            $jk = in_array($jk, ['L', 'P']) ? $jk : null;

            $exists = DB::table('anggota')->where('no_anggota', $noAnggota)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            DB::table('anggota')->insert([
                'no_anggota'       => $noAnggota,
                'nama'             => $nama,
                'email'            => $email,
                'jenis_kelamin'    => $jk,
                'pekerjaan'        => $pekerjaan ?: null,
                'id_status_anggota' => $statusAktifId,
                'aktif'            => 'Y',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            $inserted++;
        }

        $this->command->info("Anggota: {$inserted} inserted, {$skipped} skipped.");
    }

    private function importSimpanan($spreadsheet): void
    {
        $sheet = $spreadsheet->getSheetByName('SW 2025');
        $rows  = $sheet->toArray(null, true, true, false);

        $idSimpananPokok = DB::table('jenis_simpanan')->where('nama_jenis', 'Simpanan Pokok')->value('id_jenis_simpanan');
        $idSimpananWajib = DB::table('jenis_simpanan')->where('nama_jenis', 'Simpanan Wajib')->value('id_jenis_simpanan');

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $row) {
            // Validasi: col 1 = nomor (angka > 0), col 2 = KSA_xxx, col 3 = nama
            if (
                !isset($row[1], $row[2]) ||
                !is_numeric($row[1]) || $row[1] <= 0 ||
                strpos((string) $row[2], 'KSA_') !== 0
            ) {
                continue;
            }

            $noAnggota = trim($row[2]);
            $idAnggota = DB::table('anggota')->where('no_anggota', $noAnggota)->value('id_anggota');

            if (!$idAnggota) {
                $skipped++;
                continue;
            }

            // Cek apakah simpanan sudah ada untuk anggota ini
            $sudahAda = DB::table('simpanan')->where('id_anggota', $idAnggota)->exists();
            if ($sudahAda) {
                $skipped++;
                continue;
            }

            // col 4  = Simpanan Pokok
            // col 5..11 = Simpanan Wajib per tahun (2019-2025)
            // col 12 = Simpanan Sukarela (umumnya kosong)
            // col 13 = Total Simpanan Wajib
            // col 14 = Total Modal (SP + SW)
            $nominalPokok = $this->parseNominal($row[4] ?? 0);
            $nominalWajib = $this->parseNominal($row[13] ?? 0);

            $now = now();

            if ($idSimpananPokok && $nominalPokok > 0) {
                DB::table('simpanan')->insert([
                    'id_anggota'        => $idAnggota,
                    'id_jenis_simpanan' => $idSimpananPokok,
                    'nominal'           => $nominalPokok,
                    'keterangan'        => 'Import dari Excel SW 2025',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }

            if ($idSimpananWajib && $nominalWajib > 0) {
                DB::table('simpanan')->insert([
                    'id_anggota'        => $idAnggota,
                    'id_jenis_simpanan' => $idSimpananWajib,
                    'nominal'           => $nominalWajib,
                    'keterangan'        => 'Import dari Excel SW 2025 (Total 2019-2025)',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }

            $inserted++;
        }

        $this->command->info("Simpanan: {$inserted} anggota diproses, {$skipped} dilewati.");
    }

    private function parseNominal(mixed $value): int
    {
        if ($value === null || trim((string) $value) === '-' || trim((string) $value) === '') {
            return 0;
        }
        // Hapus "Rp", spasi, titik (pemisah ribuan), koma
        $clean = preg_replace('/[Rp\s\.,]/', '', (string) $value);
        return (int) $clean;
    }
}
