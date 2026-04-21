<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (role_id=2) mendapat semua 53 permission
        $rows = [];
        foreach (range(1, 53) as $pid) {
            $rows[] = ['role_id' => 2, 'permission_id' => $pid];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('role_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::table('role_permissions')->insert($rows);
    }
}
