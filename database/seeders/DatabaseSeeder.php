<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Master / lookup data (urutan penting: FK harus ada dulu)
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            StatusAnggotaSeeder::class,
            JenisSimpananSeeder::class,

            // Users & Anggota
            UserSeeder::class,
            AnggotaSeeder::class,
            UserRoleSeeder::class,

            // Transaksi
            SimpananSeeder::class,
            ShuSeeder::class,
        ]);
    }
}
