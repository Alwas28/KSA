<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class JenisSimpananPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'jenis_simpanan.read',
                'display_name' => 'Lihat Jenis Simpanan',
                'description' => 'Dapat melihat data jenis simpanan',
                'module' => 'jenis_simpanan',
                'is_active' => true,
            ],
            [
                'name' => 'jenis_simpanan.create',
                'display_name' => 'Tambah Jenis Simpanan',
                'description' => 'Dapat menambahkan jenis simpanan baru',
                'module' => 'jenis_simpanan',
                'is_active' => true,
            ],
            [
                'name' => 'jenis_simpanan.update',
                'display_name' => 'Edit Jenis Simpanan',
                'description' => 'Dapat mengedit data jenis simpanan',
                'module' => 'jenis_simpanan',
                'is_active' => true,
            ],
            [
                'name' => 'jenis_simpanan.delete',
                'display_name' => 'Hapus Jenis Simpanan',
                'description' => 'Dapat menghapus data jenis simpanan',
                'module' => 'jenis_simpanan',
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
