<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportKsaDataSeeder extends Seeder
{
    // IDs dari tabel referensi
    private const ROLE_ANGGOTA       = 3;
    private const STATUS_AKTIF       = 1;
    private const ID_JENIS_SW        = 1; // Simpanan Wajib
    private const ID_JENIS_SP        = 2; // Simpanan Pokok
    private const ADMIN_USER_ID      = 1;

    public function run(): void
    {
        $this->bersihkanData();
        $this->importDariExcel();
    }

    private function bersihkanData(): void
    {
        $this->command->info('Membersihkan data lama...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('angsuran')->truncate();
        DB::table('pinjaman')->truncate();
        DB::table('simpanan')->truncate();
        DB::table('anggota')->truncate();
        DB::table('user_roles')->where('user_id', '!=', self::ADMIN_USER_ID)->delete();
        DB::table('users')->where('id', '!=', self::ADMIN_USER_ID)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('Data lama berhasil dihapus (admin dipertahankan).');
    }

    private function importDariExcel(): void
    {
        $path        = base_path('ksa.xlsx');
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        $anggotaInserted = 0;
        $simpananInserted = 0;
        $now = now();

        foreach ($rows as $row) {
            // Validasi baris: col 0 = nomor urut (angka > 0), col 1 = KSA_xxx
            if (
                !isset($row[0], $row[1]) ||
                !is_numeric($row[0]) || (int) $row[0] <= 0 ||
                strpos((string) $row[1], 'KSA_') !== 0
            ) {
                continue;
            }

            $noUrut = trim($row[1]);               // KSA_001
            $nama   = trim($row[2] ?? '');
            $email  = $this->generateEmail($noUrut); // ksa001@umkendari.ac.id

            // 1. Buat user account
            $userId = DB::table('users')->insertGetId([
                'name'       => $nama,
                'email'      => $email,
                'password'   => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 2. Assign role Anggota
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => self::ROLE_ANGGOTA,
            ]);

            // 3. Insert anggota
            $idAnggota = DB::table('anggota')->insertGetId([
                'no_anggota'        => $noUrut,
                'nama'              => $nama,
                'email'             => $email,
                'id_status_anggota' => self::STATUS_AKTIF,
                'aktif'             => 'Y',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);

            // 4. Insert simpanan pokok (col 3) — hanya jika ada nilai
            $nominalPokok = $this->parseNominal($row[3] ?? null);
            if ($nominalPokok > 0) {
                DB::table('simpanan')->insert([
                    'id_anggota'        => $idAnggota,
                    'id_jenis_simpanan' => self::ID_JENIS_SP,
                    'nominal'           => $nominalPokok,
                    'keterangan'        => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
                $simpananInserted++;
            }

            // 5. Insert simpanan wajib (col 4)
            $nominalWajib = $this->parseNominal($row[4] ?? null);
            if ($nominalWajib > 0) {
                DB::table('simpanan')->insert([
                    'id_anggota'        => $idAnggota,
                    'id_jenis_simpanan' => self::ID_JENIS_SW,
                    'nominal'           => $nominalWajib,
                    'keterangan'        => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
                $simpananInserted++;
            }

            $anggotaInserted++;
        }

        $this->command->info("Anggota diimport  : {$anggotaInserted}");
        $this->command->info("Simpanan diimport : {$simpananInserted}");
        $this->command->info("User dibuat       : {$anggotaInserted}");
    }

    // KSA_001 → ksa001@umkendari.ac.id
    private function generateEmail(string $noUrut): string
    {
        $prefix = strtolower(str_replace('_', '', $noUrut));
        return $prefix . '@umkendari.ac.id';
    }

    private function parseNominal(mixed $value): int
    {
        if ($value === null) return 0;
        $str = trim((string) $value);
        if ($str === '' || $str === '-') return 0;
        // Hapus Rp, spasi, titik ribuan, koma desimal
        $clean = preg_replace('/[Rp\s\.,]/', '', $str);
        return (int) $clean;
    }
}
