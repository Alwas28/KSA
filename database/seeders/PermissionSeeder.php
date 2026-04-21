<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['id' => 1,  'name' => 'user.read',              'display_name' => 'Lihat User',              'description' => 'Melihat user',                          'module' => 'users',          'is_active' => true, 'created_at' => '2025-12-12 21:47:21', 'updated_at' => '2025-12-12 21:58:17'],
            ['id' => 2,  'name' => 'user.create',            'display_name' => 'Buat User',               'description' => 'Tambah user',                           'module' => 'users',          'is_active' => true, 'created_at' => '2025-12-12 21:48:05', 'updated_at' => '2025-12-12 21:48:05'],
            ['id' => 3,  'name' => 'user.update',            'display_name' => 'Ubah User',               'description' => 'Perbaharui user',                       'module' => 'users',          'is_active' => true, 'created_at' => '2025-12-12 21:48:34', 'updated_at' => '2025-12-12 21:58:27'],
            ['id' => 4,  'name' => 'user.delete',            'display_name' => 'Hapus User',              'description' => 'Menghapus user',                        'module' => 'users',          'is_active' => true, 'created_at' => '2025-12-12 21:49:08', 'updated_at' => '2025-12-12 21:57:36'],

            // Roles
            ['id' => 5,  'name' => 'role.read',              'display_name' => 'lihat role',              'description' => 'Melihat role akses',                    'module' => 'roles',          'is_active' => true, 'created_at' => '2025-12-12 21:54:12', 'updated_at' => '2025-12-12 21:54:12'],
            ['id' => 6,  'name' => 'role.create',            'display_name' => 'Tambah role',             'description' => 'Menambah role',                         'module' => 'roles',          'is_active' => true, 'created_at' => '2025-12-12 21:54:42', 'updated_at' => '2025-12-12 21:54:42'],
            ['id' => 7,  'name' => 'role.update',            'display_name' => 'Update role',             'description' => 'Mengedit role',                         'module' => 'roles',          'is_active' => true, 'created_at' => '2025-12-12 21:55:00', 'updated_at' => '2025-12-12 21:55:00'],
            ['id' => 8,  'name' => 'role.delete',            'display_name' => 'Hapus role',              'description' => 'Menghapus role',                        'module' => 'roles',          'is_active' => true, 'created_at' => '2025-12-12 21:55:19', 'updated_at' => '2025-12-12 21:55:19'],

            // Anggota
            ['id' => 9,  'name' => 'anggota.read',           'display_name' => 'Lihat Anggota',           'description' => 'Dapat melihat data anggota',             'module' => 'anggota',        'is_active' => true, 'created_at' => '2025-12-12 22:56:05', 'updated_at' => '2025-12-12 22:56:05'],
            ['id' => 10, 'name' => 'anggota.create',         'display_name' => 'Tambah Anggota',          'description' => 'Dapat menambahkan anggota baru',         'module' => 'anggota',        'is_active' => true, 'created_at' => '2025-12-12 22:56:05', 'updated_at' => '2025-12-12 22:56:05'],
            ['id' => 11, 'name' => 'anggota.update',         'display_name' => 'Edit Anggota',            'description' => 'Dapat mengedit data anggota',            'module' => 'anggota',        'is_active' => true, 'created_at' => '2025-12-12 22:56:05', 'updated_at' => '2025-12-12 22:56:05'],
            ['id' => 12, 'name' => 'anggota.delete',         'display_name' => 'Hapus Anggota',           'description' => 'Dapat menghapus data anggota',           'module' => 'anggota',        'is_active' => true, 'created_at' => '2025-12-12 22:56:05', 'updated_at' => '2025-12-12 22:56:05'],

            // Jenis Simpanan
            ['id' => 13, 'name' => 'jenis_simpanan.read',    'display_name' => 'Lihat Jenis Simpanan',    'description' => 'Dapat melihat data jenis simpanan',      'module' => 'jenis_simpanan', 'is_active' => true, 'created_at' => '2025-12-12 23:58:14', 'updated_at' => '2025-12-13 00:03:04'],
            ['id' => 14, 'name' => 'jenis_simpanan.create',  'display_name' => 'Tambah Jenis Simpanan',   'description' => 'Dapat menambahkan jenis simpanan baru',  'module' => 'jenis_simpanan', 'is_active' => true, 'created_at' => '2025-12-12 23:58:43', 'updated_at' => '2025-12-13 00:03:04'],
            ['id' => 15, 'name' => 'jenis_simpanan.update',  'display_name' => 'Edit Jenis Simpanan',     'description' => 'Dapat mengedit data jenis simpanan',     'module' => 'jenis_simpanan', 'is_active' => true, 'created_at' => '2025-12-12 23:59:17', 'updated_at' => '2025-12-13 00:03:04'],
            ['id' => 16, 'name' => 'jenis_simpanan.delete',  'display_name' => 'Hapus Jenis Simpanan',    'description' => 'Dapat menghapus data jenis simpanan',    'module' => 'jenis_simpanan', 'is_active' => true, 'created_at' => '2025-12-12 23:59:40', 'updated_at' => '2025-12-13 00:03:04'],

            // Simpanan
            ['id' => 17, 'name' => 'simpanan.delete',        'display_name' => 'Hapus Simpanan',          'description' => 'Hapus Simpanan',                         'module' => 'simpanan',       'is_active' => true, 'created_at' => '2025-12-13 04:15:22', 'updated_at' => '2025-12-13 04:15:22'],
            ['id' => 18, 'name' => 'simpanan.update',        'display_name' => 'Update Simpanan',         'description' => 'Update Simpanan',                        'module' => 'simpanan',       'is_active' => true, 'created_at' => '2025-12-13 04:15:40', 'updated_at' => '2025-12-13 04:15:40'],
            ['id' => 19, 'name' => 'simpanan.create',        'display_name' => 'Tambah Simpanan',         'description' => 'Tambah Simpanan',                        'module' => 'simpanan',       'is_active' => true, 'created_at' => '2025-12-13 04:15:59', 'updated_at' => '2025-12-13 04:15:59'],
            ['id' => 20, 'name' => 'simpanan.read',          'display_name' => 'Lihat Simpanan',          'description' => 'Lihat Simpanan',                         'module' => 'simpanan',       'is_active' => true, 'created_at' => '2025-12-13 04:16:17', 'updated_at' => '2025-12-13 04:16:17'],

            // Status Anggota
            ['id' => 21, 'name' => 'status_anggota.read',    'display_name' => 'Lihat Status Anggota',    'description' => 'Status anggota',                         'module' => 'status_anggota', 'is_active' => true, 'created_at' => '2025-12-13 07:31:18', 'updated_at' => '2025-12-13 07:31:18'],
            ['id' => 22, 'name' => 'status_anggota.create',  'display_name' => 'Tambah Status Anggota',   'description' => 'Status anggota',                         'module' => 'status_anggota', 'is_active' => true, 'created_at' => '2025-12-13 07:31:32', 'updated_at' => '2025-12-13 07:31:32'],
            ['id' => 23, 'name' => 'status_anggota.update',  'display_name' => 'Update Status Anggota',   'description' => 'Status anggota',                         'module' => 'status_anggota', 'is_active' => true, 'created_at' => '2025-12-13 07:31:46', 'updated_at' => '2025-12-13 07:31:46'],
            ['id' => 24, 'name' => 'status_anggota.delete',  'display_name' => 'Delete Status Anggota',   'description' => 'Status anggota',                         'module' => 'status_anggota', 'is_active' => true, 'created_at' => '2025-12-13 07:31:58', 'updated_at' => '2025-12-13 07:31:58'],

            // Pinjaman
            ['id' => 25, 'name' => 'pinjaman.read',          'display_name' => 'Lihat Pinjaman',          'description' => 'pinjaman',                               'module' => 'pinjaman',       'is_active' => true, 'created_at' => '2025-12-13 12:34:28', 'updated_at' => '2025-12-13 12:34:28'],
            ['id' => 26, 'name' => 'pinjaman.create',        'display_name' => 'Tambah Pinjaman',         'description' => 'Pinjaman',                               'module' => 'pinjaman',       'is_active' => true, 'created_at' => '2025-12-13 12:34:54', 'updated_at' => '2025-12-13 12:34:54'],
            ['id' => 27, 'name' => 'pinjaman.update',        'display_name' => 'Update Pinjaman',         'description' => 'Pinjaman',                               'module' => 'pinjaman',       'is_active' => true, 'created_at' => '2025-12-13 12:35:15', 'updated_at' => '2025-12-13 12:35:15'],
            ['id' => 28, 'name' => 'pinjaman.delete',        'display_name' => 'Hapus Pinjaman',          'description' => 'Pinjaman',                               'module' => 'pinjaman',       'is_active' => true, 'created_at' => '2025-12-13 12:35:32', 'updated_at' => '2025-12-13 12:35:32'],

            // Berita
            ['id' => 50, 'name' => 'berita.read',            'display_name' => 'Lihat Berita',            'description' => 'Melihat daftar berita',                  'module' => 'berita',         'is_active' => true, 'created_at' => '2026-04-21 00:00:00', 'updated_at' => '2026-04-21 00:00:00'],
            ['id' => 51, 'name' => 'berita.create',          'display_name' => 'Tambah Berita',           'description' => 'Menambahkan berita baru',                'module' => 'berita',         'is_active' => true, 'created_at' => '2026-04-21 00:00:00', 'updated_at' => '2026-04-21 00:00:00'],
            ['id' => 52, 'name' => 'berita.edit',            'display_name' => 'Edit Berita',             'description' => 'Mengedit berita',                        'module' => 'berita',         'is_active' => true, 'created_at' => '2026-04-21 00:00:00', 'updated_at' => '2026-04-21 00:00:00'],
            ['id' => 53, 'name' => 'berita.delete',          'display_name' => 'Hapus Berita',            'description' => 'Menghapus berita',                       'module' => 'berita',         'is_active' => true, 'created_at' => '2026-04-21 00:00:00', 'updated_at' => '2026-04-21 00:00:00'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(['id' => $permission['id']], $permission);
        }
    }
}
