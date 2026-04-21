<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id'           => 2,
                'name'         => 'Admin',
                'display_name' => 'Administrator',
                'description'  => '-',
                'is_active'    => true,
                'created_at'   => '2025-12-12 21:45:53',
                'updated_at'   => '2025-12-12 22:05:40',
            ],
            [
                'id'           => 3,
                'name'         => 'Anggota',
                'display_name' => 'Anggota',
                'description'  => 'akses anggota',
                'is_active'    => true,
                'created_at'   => '2025-12-13 21:40:17',
                'updated_at'   => '2025-12-13 21:40:26',
            ],
            [
                'id'           => 4,
                'name'         => 'Ketua',
                'display_name' => 'pengurus',
                'description'  => '-',
                'is_active'    => true,
                'created_at'   => '2026-01-02 13:22:44',
                'updated_at'   => '2026-01-02 13:22:44',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
        }
    }
}
