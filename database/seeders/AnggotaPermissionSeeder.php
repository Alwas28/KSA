<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AnggotaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'anggota.read',
                'display_name' => 'Lihat Anggota',
                'description' => 'Dapat melihat data anggota',
                'module' => 'anggota',
                'is_active' => true,
            ],
            [
                'name' => 'anggota.create',
                'display_name' => 'Tambah Anggota',
                'description' => 'Dapat menambahkan anggota baru',
                'module' => 'anggota',
                'is_active' => true,
            ],
            [
                'name' => 'anggota.update',
                'display_name' => 'Edit Anggota',
                'description' => 'Dapat mengedit data anggota',
                'module' => 'anggota',
                'is_active' => true,
            ],
            [
                'name' => 'anggota.delete',
                'display_name' => 'Hapus Anggota',
                'description' => 'Dapat menghapus data anggota',
                'module' => 'anggota',
                'is_active' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
