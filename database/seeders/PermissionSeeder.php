<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['id'=>1, 'name'=>'user.read',               'display_name'=>'Lihat User',                'description'=>'Melihat user',                    'module'=>'users'],
            ['id'=>2, 'name'=>'user.create',              'display_name'=>'Buat User',                 'description'=>'Tambah user',                     'module'=>'users'],
            ['id'=>3, 'name'=>'user.update',              'display_name'=>'Ubah User',                 'description'=>'Perbaharui user',                 'module'=>'users'],
            ['id'=>4, 'name'=>'user.delete',              'display_name'=>'Hapus User',                'description'=>'Menghapus user',                  'module'=>'users'],
            ['id'=>5, 'name'=>'role.read',                'display_name'=>'Lihat Role',                'description'=>'Melihat role akses',              'module'=>'roles'],
            ['id'=>6, 'name'=>'role.create',              'display_name'=>'Tambah Role',               'description'=>'Menambah role',                   'module'=>'roles'],
            ['id'=>7, 'name'=>'role.update',              'display_name'=>'Update Role',               'description'=>'Mengedit role',                   'module'=>'roles'],
            ['id'=>8, 'name'=>'role.delete',              'display_name'=>'Hapus Role',                'description'=>'Menghapus role',                  'module'=>'roles'],
            ['id'=>9, 'name'=>'anggota.read',             'display_name'=>'Lihat Anggota',             'description'=>'Dapat melihat data anggota',      'module'=>'anggota'],
            ['id'=>10,'name'=>'anggota.create',           'display_name'=>'Tambah Anggota',            'description'=>'Dapat menambahkan anggota baru',  'module'=>'anggota'],
            ['id'=>11,'name'=>'anggota.update',           'display_name'=>'Edit Anggota',              'description'=>'Dapat mengedit data anggota',     'module'=>'anggota'],
            ['id'=>12,'name'=>'anggota.delete',           'display_name'=>'Hapus Anggota',             'description'=>'Dapat menghapus data anggota',    'module'=>'anggota'],
            ['id'=>13,'name'=>'jenis_simpanan.read',      'display_name'=>'Lihat Jenis Simpanan',      'description'=>'Dapat melihat data jenis simpanan',    'module'=>'jenis_simpanan'],
            ['id'=>14,'name'=>'jenis_simpanan.create',    'display_name'=>'Tambah Jenis Simpanan',     'description'=>'Dapat menambahkan jenis simpanan baru','module'=>'jenis_simpanan'],
            ['id'=>15,'name'=>'jenis_simpanan.update',    'display_name'=>'Edit Jenis Simpanan',       'description'=>'Dapat mengedit data jenis simpanan',   'module'=>'jenis_simpanan'],
            ['id'=>16,'name'=>'jenis_simpanan.delete',    'display_name'=>'Hapus Jenis Simpanan',      'description'=>'Dapat menghapus data jenis simpanan',  'module'=>'jenis_simpanan'],
            ['id'=>17,'name'=>'simpanan.delete',          'display_name'=>'Hapus Simpanan',            'description'=>'Hapus Simpanan',                  'module'=>'simpanan'],
            ['id'=>18,'name'=>'simpanan.update',          'display_name'=>'Update Simpanan',           'description'=>'Update Simpanan',                 'module'=>'simpanan'],
            ['id'=>19,'name'=>'simpanan.create',          'display_name'=>'Tambah Simpanan',           'description'=>'Tambah Simpanan',                 'module'=>'simpanan'],
            ['id'=>20,'name'=>'simpanan.read',            'display_name'=>'Lihat Simpanan',            'description'=>'Lihat Simpanan',                  'module'=>'simpanan'],
            ['id'=>21,'name'=>'status_anggota.read',      'display_name'=>'Lihat Status Anggota',      'description'=>'Status anggota',                  'module'=>'status_anggota'],
            ['id'=>22,'name'=>'status_anggota.create',    'display_name'=>'Tambah Status Anggota',     'description'=>'Status anggota',                  'module'=>'status_anggota'],
            ['id'=>23,'name'=>'status_anggota.update',    'display_name'=>'Update Status Anggota',     'description'=>'Status anggota',                  'module'=>'status_anggota'],
            ['id'=>24,'name'=>'status_anggota.delete',    'display_name'=>'Delete Status Anggota',     'description'=>'Status anggota',                  'module'=>'status_anggota'],
            ['id'=>25,'name'=>'pinjaman.read',            'display_name'=>'Lihat Pinjaman',            'description'=>'pinjaman',                        'module'=>'pinjaman'],
            ['id'=>26,'name'=>'pinjaman.create',          'display_name'=>'Tambah Pinjaman',           'description'=>'Pinjaman',                        'module'=>'pinjaman'],
            ['id'=>27,'name'=>'pinjaman.update',          'display_name'=>'Update Pinjaman',           'description'=>'Pinjaman',                        'module'=>'pinjaman'],
            ['id'=>28,'name'=>'pinjaman.delete',          'display_name'=>'Hapus Pinjaman',            'description'=>'Pinjaman',                        'module'=>'pinjaman'],
            ['id'=>29,'name'=>'laporan_keuangan.read',    'display_name'=>'Lihat Laporan Keuangan',    'description'=>'Laporan Keuangan',                'module'=>'laporan'],
            ['id'=>30,'name'=>'laporan_shu.read',         'display_name'=>'Lihat Laporan SHU',         'description'=>'Laporan SHU',                     'module'=>'laporan'],
            ['id'=>31,'name'=>'arsip.read',               'display_name'=>'Lihat Arsip',               'description'=>'Arsip',                           'module'=>'arsip'],
            ['id'=>32,'name'=>'arsip.create',             'display_name'=>'Tambah Arsip',              'description'=>'Arsip',                           'module'=>'arsip'],
            ['id'=>33,'name'=>'arsip.edit',               'display_name'=>'Edit Arsip',                'description'=>'Arsip',                           'module'=>'arsip'],
            ['id'=>34,'name'=>'arsip.delete',             'display_name'=>'Hapus Arsip',               'description'=>'Arsip',                           'module'=>'arsip'],
            ['id'=>35,'name'=>'master_kegiatan.read',     'display_name'=>'Lihat Master Kegiatan',     'description'=>'Master Kegiatan',                 'module'=>'master_kegiatan'],
            ['id'=>36,'name'=>'master_kegiatan.create',   'display_name'=>'Tambah Master Kegiatan',    'description'=>'Master Kegiatan',                 'module'=>'master_kegiatan'],
            ['id'=>37,'name'=>'master_kegiatan.edit',     'display_name'=>'Edit Master Kegiatan',      'description'=>'Master Kegiatan',                 'module'=>'master_kegiatan'],
            ['id'=>38,'name'=>'master_kegiatan.delete',   'display_name'=>'Hapus Master Kegiatan',     'description'=>'Master Kegiatan',                 'module'=>'master_kegiatan'],
            ['id'=>39,'name'=>'menu_manajemen_post.read', 'display_name'=>'Lihat Menu Manajemen Post', 'description'=>'Lihat Menu Manajemen Post',        'module'=>'lihat_menu'],
            ['id'=>40,'name'=>'menu_master_data.read',    'display_name'=>'Lihat Menu Master Data',    'description'=>'Lihat Menu Master Data',           'module'=>'lihat_menu'],
            ['id'=>41,'name'=>'menu_manajemen_akses.read','display_name'=>'Lihat Menu Manajemen Akses','description'=>'Lihat Menu Manajemen Akses',       'module'=>'lihat_menu'],
            ['id'=>42,'name'=>'permission.read',          'display_name'=>'Lihat Permission',          'description'=>'Permission',                      'module'=>'permission'],
            ['id'=>43,'name'=>'permission.create',        'display_name'=>'Tambah Permission',         'description'=>'Permission',                      'module'=>'permission'],
            ['id'=>44,'name'=>'permission.edit',          'display_name'=>'Edit Permission',           'description'=>'Permission',                      'module'=>'permission'],
            ['id'=>45,'name'=>'permission.delete',        'display_name'=>'Hapus Permission',          'description'=>'Permission',                      'module'=>'permission'],
            ['id'=>46,'name'=>'user_role.read',           'display_name'=>'Lihat User Role',           'description'=>'User Roles',                      'module'=>'user_role'],
            ['id'=>47,'name'=>'user_role.edit',           'display_name'=>'Kelola User Role',          'description'=>'User Roles',                      'module'=>'user_role'],
            ['id'=>48,'name'=>'role_permission.read',     'display_name'=>'Lihat Role Permission',     'description'=>'Role Permission',                 'module'=>'role_permission'],
            ['id'=>49,'name'=>'role_permission.edit',     'display_name'=>'Kelola Role Permission',    'description'=>'Role Permission',                 'module'=>'role_permission'],
            ['id'=>50,'name'=>'berita.read',              'display_name'=>'Lihat Berita',              'description'=>'Melihat daftar berita',           'module'=>'berita'],
            ['id'=>51,'name'=>'berita.create',            'display_name'=>'Tambah Berita',             'description'=>'Menambahkan berita baru',         'module'=>'berita'],
            ['id'=>52,'name'=>'berita.edit',              'display_name'=>'Edit Berita',               'description'=>'Mengedit berita',                 'module'=>'berita'],
            ['id'=>53,'name'=>'berita.delete',            'display_name'=>'Hapus Berita',              'description'=>'Menghapus berita',                'module'=>'berita'],
        ];

        foreach ($permissions as &$p) {
            $p['is_active']  = 1;
            $p['created_at'] = now();
            $p['updated_at'] = now();
        }

        DB::table('permissions')->upsert($permissions, ['id'], ['name','display_name','description','module','is_active']);
    }
}
